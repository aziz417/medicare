<tr @if($patient->isSubmember()) class="sub-member" @endif >
    <td class="d-flex align-items-center">
        <img src="{{ asset($patient->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
        <div class="ml-2">
            <strong>{{ $patient->name }}</strong><br>
            @if($patient->isSubmember())
            <small><i><strong>{{ $patient->getMeta('relationship_with_member', 'Member') }}</strong> of {{ $patient->member->name ?? '' }}</i></small>
            @endif
        </div>
    </td>
    <td>
        <div class="text-muted text-nowrap">
            {{ $patient->getMeta('user_gender', '~') }}
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-email p-0 mr-2"></span> {{ $patient->email }}</div>
    </td>
    <td>
        <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-cell-phone p-0 mr-2"></span> {{ $patient->mobile }}</div>
    </td>
    <td>
        <div class="address-col">{{ autop($patient->getMeta('user_address', '~')) }}</div>
    </td>
    <td>
        <div class="text-muted text-nowrap"><div class="badge badge-{{ $patient->status=='active' ? 'success': 'warning' }}">{{ ucfirst($patient->status ?? 'active') }}</div></div>
    </td>
    <td>
        <form class="actions" action="{{ route('admin.patients.destroy', $patient->id) }}" onsubmit="return confirm('Are you sure?')" method="POST">
            @csrf @method('DELETE')
            <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill"><span class="btn-icon icofont-eye-alt"></span></a>
            <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-info btn-sm btn-square rounded-pill"><span class="btn-icon icofont-ui-edit"></span></a>
            @if( $auth->isAdmin(false) )
            <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit"><span class="btn-icon icofont-trash"></span></button>
            @endif
        </form>
    </td>
</tr>
@forelse( $patient->subMembers as $member )
    @include('admin.patients.list-item', ['patient' => $member])
@empty
@endforelse