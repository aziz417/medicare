<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SubMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = User::where('submember_of', auth()->id())->get(); // ->with('member')
        return view('user.sub-members', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'mobile' => 'nullable|string|mobile|unique:users',
            'gender' => 'required|string',
            'user_age' => 'nullable|numeric',
            'user_blood_group' => 'nullable|string',
            'relationship_with_member' => 'required|string',
        ]);
        $myMembersTotal = User::where('submember_of', auth()->id())->count();
        $max = config('system.max_sub_members_of_a_member', 100);
        if( !($max > $myMembersTotal) ){
            return back()->withInput()->withInfo("You can add maximum {$max} members!");
        }
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
            'role' => 'user',
            'password' => 'no-password-for-sub-member',
            'submember_of' => auth()->id()
        ]);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $user->fill([ 'picture' => $image ])->save();
        }
        if( $request->has('auto_verified') ){
            $user->forceFill([
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ]);
        }
        if( $user ){
            $user->setMeta('relationship_with_member', $validated['relationship_with_member'] ?? null);
            $user->setMeta('user_gender', $validated['gender'] ?? null);
            $user->setMeta('user_age', $validated['user_age'] ?? null);
            $user->setMeta('user_blood_group', $validated['user_blood_group'] ?? null);
            return back()->withSuccess('Member Created Successfully!');
        }
        return back()->withInfo('Something is wrong, try again!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $sub_member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $sub_member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "nullable|email|max:255|unique:users,email,".$sub_member->id,
            'mobile' => "nullable|mobile|unique:users,mobile,".$sub_member->id,
            'gender' => 'required|string',
            'user_age' => 'nullable|numeric',
            'user_blood_group' => 'nullable|string',
            'relationship_with_member' => 'required|string',
        ]);

        $sub_member->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
        ]);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $sub_member->fill([ 'picture' => $image ]);
        }
        if( $sub_member->save() ){
            $sub_member->setMeta('relationship_with_member', $validated['relationship_with_member'] ?? null);
            $sub_member->setMeta('user_gender', $validated['gender'] ?? null);
            $sub_member->setMeta('user_age', $validated['user_age'] ?? null);
            $sub_member->setMeta('user_blood_group', $validated['user_blood_group'] ?? null);
            return back()->withSuccess('Member Update Successfully!');
        }
        return back()->withInfo('Something is wrong, try again!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $sub_member
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sub_member)
    {
        if( $sub_member->delete() ){
            $sub_member->meta()->delete();
            return back()->withSuccess('Member Deleted Successfully!');
        }
        return back()->withInfo('Something is wrong, try again!');
    }
}
