
<div class="p-3">
    <form id="add-investigation-update" action="{{ route('admin.investigation.customUpdate') }}"
          method="POST"
          class="form-inline row justify-content-between">
        @csrf
        @method('put')
        <input id="investigations-user-id" type="hidden" name="id"
               value="{{ $investigation->id ?? null }}">
        <input id="investi-user-id" type="hidden" name="user_id"
               value="{{ $investigation->user_id ?? null }}">
        <div class="row px-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Investigation Title</label>
                    <input name="title" value="{{ $investigation->title }}" id="title-update" placeholder="Title" type="text"
                           class="form-control form-control-sm w-100" list="investigations-list"
                           autocomplete="off" required>
                    <datalist id="investigations-list">
                        @foreach(config('system.investigations', []) as $iName)
                            <option>{{ $iName }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Details</label>
                    <input name="details" value="{{ $investigation->details }}" id="details-update" placeholder="Details" type="text"
                           class="form-control form-control-sm w-100" required>
                </div>
            </div>
            @forelse($investigation->data as $reportInfo)
                <div class="row">
                    <div class="form-group col-6">
                        <label class="control-label">Date</label>
                        <br>
                        <span>{{ $reportInfo['date'] }}</span>
                        <input name="oldDate[]" type="hidden" value="{{ $reportInfo['date'] }}" placeholder="Date">
                        <input name="data_date[]" id="date-update"  placeholder="Date" type="date"
                               class="form-control form-control-sm w-100">
                    </div>
                    <div class="form-group col-6">
                        <label class="control-label">Report</label>
                        <textarea name="update_details[]" id="report-update" cols="30" rows="1"
                                  class="form-control form-control-sm w-100"
                                  placeholder="Investigation result">{{ $reportInfo['details'] }}</textarea>
                    </div>
                </div>
            @empty
                <p>No Report</p>
            @endforelse

            <div class="col-md-2 mt-2 text-right">
                <button type="submit" id="inv-submit" class="btn btn-sm btn-primary">
                                <span class="mr-2 loader spinner-border-sm spinner-border text-white d-none"
                                      role="status"><span class="sr-only">Loading...</span></span>
                    Update
                </button>
                <br>
                <br>
            </div>
        </div>

    </form>
</div>

