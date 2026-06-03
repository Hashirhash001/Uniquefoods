<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyAccount;
use App\Models\CustomerGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = CompanyAccount::withCount('users')
            ->with(['groups', 'activeUsers'])
            ->when($request->filled('search'), fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('primary_email', 'like', "%{$request->search}%")
            )
            ->latest()->paginate(20);

        $groups = CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        if ($request->ajax()) {
            $html = $companies->map(fn($company) =>
                view('admin.companies._row', compact('company'))->render()
            )->implode('');

            return response()->json([
                'html'       => $html,
                'pagination' => $companies->links('pagination::bootstrap-5')->render(),
                'from'       => $companies->firstItem() ?? 0,
                'to'         => $companies->lastItem()  ?? 0,
                'total'      => $companies->total(),
            ]);
        }

        return view('admin.companies.index', compact('companies', 'groups'));
    }

    public function userSearch(Request $request)
    {
        $q         = trim($request->get('q', ''));
        $excludeId = $request->get('exclude_company_id'); // current company being edited

        $users = User::where('is_admin', 0)
            ->where(fn($query) => $query
                ->where('name',  'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
            )
            ->with('companies:id,name')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users->map(function ($user) use ($excludeId) {
            $company = $user->companies->first();
            return [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'company'     => $company && $company->id != $excludeId
                                    ? ['id' => $company->id, 'name' => $company->name]
                                    : null,
            ];
        }));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'primary_email' => 'required|email',
            'phone'         => 'nullable|string|max:30',
            'address'       => 'nullable|string|max:500',
            'group_ids'     => 'nullable|array',
            'group_ids.*'   => 'exists:customer_groups,id',
            'user_ids'      => 'nullable|array',
            'user_ids.*'    => 'exists:users,id',
            'owner_id'      => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $company = CompanyAccount::create($request->only(
                'name', 'primary_email', 'phone', 'address'
            ));

            if ($request->filled('group_ids')) {
                $company->groups()->sync($request->group_ids);
            }

            // Attach users
            $userIds = collect($request->user_ids ?? []);
            $ownerId = $request->owner_id;

            $sync = [];
            foreach ($userIds as $uid) {
                $sync[$uid] = ['role' => $uid == $ownerId ? 'owner' : 'member', 'is_active' => true];
            }
            if ($ownerId && !$userIds->contains($ownerId)) {
                $sync[$ownerId] = ['role' => 'owner', 'is_active' => true];
            }

            // Enforce single-company: detach these users from any other company first
            $allUserIds = array_keys($sync);
            if (!empty($allUserIds)) {
                DB::table('company_users')
                    ->whereIn('user_id', $allUserIds)
                    ->where('company_account_id', '!=', $company->id)
                    ->delete();
            }

            $company->users()->sync($sync);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Company created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, CompanyAccount $company)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'primary_email' => 'required|email',
            'group_ids'     => 'nullable|array',
            'group_ids.*'   => 'exists:customer_groups,id',
            'user_ids'      => 'nullable|array',
            'user_ids.*'    => 'exists:users,id',
            'owner_id'      => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $company->update($request->only('name', 'primary_email', 'phone', 'address', 'is_active'));
            $company->groups()->sync($request->group_ids ?? []);

            $userIds = collect($request->user_ids ?? []);
            $ownerId = $request->owner_id;
            $sync = [];
            foreach ($userIds as $uid) {
                $sync[$uid] = ['role' => $uid == $ownerId ? 'owner' : 'member', 'is_active' => true];
            }
            if ($ownerId && !$userIds->contains($ownerId)) {
                $sync[$ownerId] = ['role' => 'owner', 'is_active' => true];
            }

            // Enforce single-company: detach these users from any other company first
            $allUserIds = array_keys($sync);
            if (!empty($allUserIds)) {
                DB::table('company_users')
                    ->whereIn('user_id', $allUserIds)
                    ->where('company_account_id', '!=', $company->id)
                    ->delete();
            }

            $company->users()->sync($sync);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Company updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(CompanyAccount $company)
    {
        $company->load(['users.groups', 'groups']);
        $allGroups = CustomerGroup::where('is_active', 1)->get();
        $allUsers  = User::where('is_admin', 0)->orderBy('name')->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'company' => $company,
            'users'   => $company->users,
            'groups'  => $company->groups,
            'allGroups' => $allGroups,
            'allUsers'  => $allUsers,
        ]);
    }

    public function destroy(CompanyAccount $company)
    {
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Company deleted']);
    }

    // Add/remove a single user from a company (for quick actions)
    public function addUser(Request $request, CompanyAccount $company)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        // Remove from any existing company first (single-company rule)
        DB::table('company_users')
            ->where('user_id', $request->user_id)
            ->where('company_account_id', '!=', $company->id)
            ->delete();

        $company->users()->syncWithoutDetaching([
            $request->user_id => ['role' => 'member', 'is_active' => true]
        ]);

        return response()->json(['success' => true, 'message' => 'User added to company']);
    }

    public function removeUser(Request $request, CompanyAccount $company)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $company->users()->detach($request->user_id);
        return response()->json(['success' => true, 'message' => 'User removed from company']);
    }
}
