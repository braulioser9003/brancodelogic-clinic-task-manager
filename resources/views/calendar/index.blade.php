@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 mb-4">
                            <div class="d-grid">
                                <button class="btn btn-lg font-16 btn-primary" id="btn-new-event"><i class="mdi mdi-plus-circle-outline"></i> Create New Event</button>
                            </div>

                        </div> <!-- end col-->


                        <div class="col-xl-12">
                            <div class="mt-4 mt-xl-0">
                                <div id="calendar"></div>
                            </div>
                        </div> <!-- end col -->

                    </div>  <!-- end row -->
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0 d-block">
                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="modal-title">Event</h5>
                        </div>
                        <div class="modal-body px-4 pb-4 pt-0">
                            <form  name="event-form" id="form-event" novalidate>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Event Name</label>
                                            <input class="form-control" placeholder="Insert Event Name" type="text" name="title" id="event-title" />
                                            <span class="text-danger error-title"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Description</label>
                                            <textarea class="form-control" placeholder="Insert Event Description" name="description" id="event-description"></textarea>
                                            <span class="text-danger error-description"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Start Date</label>
                                            <input class="form-control" type="datetime-local" name="date_start" id="event-datestart" />
                                            <span class="text-danger error-date_start"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">End Date</label>
                                            <input class="form-control" type="datetime-local" name="date_end" id="event-dateend" />
                                            <span class="text-danger error-date_end"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Location</label>
                                            <input class="form-control" placeholder="Insert Event location" type="text" name="location" id="event-location" />
                                            <span class="text-danger error-location"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Reminder Type</label>
                                            <select class="form-control form-select" name="type_reminder" id="event-type-reminder">
                                                <option value="">-- Select Reminder Type --</option>
                                                <option value="Email">Email</option>
                                                <option value="SMS">SMS</option>
                                            </select>
                                            <span class="text-danger error-type_reminder"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Responsible</label>
                                            <select class="form-control form-select" name="user_id" id="event-user-id" required>
                                                <option value="">-- Select User --</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-user_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Event Type</label>
                                            <select class="form-control form-select" name="type_event" id="event-type-event">
                                                <option value="">-- Select Event Type --</option>
                                                <option value="Surgery">Surgery</option>
                                                <option value="Appointment">Appointment</option>
                                                <option value="Follow Up">Follow Up</option>
                                            </select>
                                            <span class="text-danger error-type_event"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="control-label form-label">Category</label>
                                            <select class="form-control form-select" name="category" id="event-category">
                                                <option value="bg-danger" selected>Danger</option>
                                                <option value="bg-success">Success</option>
                                                <option value="bg-primary">Primary</option>
                                                <option value="bg-info">Info</option>
                                                <option value="bg-dark">Dark</option>
                                                <option value="bg-warning">Warning</option>
                                            </select>
                                            <span class="text-danger error-category"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> <!-- end modal-content-->
                </div> <!-- end modal dialog-->
            </div>
            <!-- end modal-->
        </div>
        <!-- end col-12 -->
    </div> <!-- end row -->
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Function to hide validation messages
        function hideValidationMessage() {
            $(this).next('.text-danger').text('');
        }

        // Attach event listeners to form fields
        $('#form-event input, #form-event textarea, #form-event select').on('input change', hideValidationMessage);
    });

</script>
@endsection







