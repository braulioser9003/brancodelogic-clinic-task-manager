@extends('layouts.app')

@section('content')
<div class="row" style="margin: 35px 0 0 0">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-3">Users List</h3>

                <div class="row g-2 align-items-center mb-3">
                    <div class="col-md-2">
                        <div>
                            <select id="demo-foo-filter-status" class="form-select form-select-sm">
                                <option value="">-- Bulck Action --</option>
                                <option value="delete" class="text-danger">Delete</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary btn-rounded waves-effect waves-light" id="executeBulckAction">Save</button>
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-primary btn-rounded waves-effect waves-light float-right" id="addNewUser">Add New</button>
                    </div>
                </div>

                <table id="userListTable" class="table dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Is Verify</th>
                        <th>Role</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- Modal to show edit form user -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- Form to edit user -->
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        let $table = $("#userListTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.index') }}",
                type: "GET",
                data: function (d) {
                    d.search = d.search.value || "";
                    d.order = d.order ? d.order[0] : {};
                },
            },
            columns: [
                {
                    data: "id",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (row.role === "superadmin") {
                            return "";
                        } else {
                            return `<input type="checkbox" class="selectRow" data-id="${data}">`;
                        }
                    },
                },
                { data: "name", name: "name" },
                { data: "email", name: "email" },
                {
                    data: "is_verify",
                    name: "is_verify",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let checked = data ? "checked" : "";
                        let readonly = row.role === "superadmin" ? "disabled" : "";
                        let dataId = row.role === "superadmin" ? "" : `data-id="${row.id}"`;
                        return `<input class="verify" type="checkbox" ${checked} ${dataId} data-plugin="switchery" data-size="small" data-color="#039cfd" ${readonly}/>`;
                    },
                },
                { data: "role", name: "role" },
                {
                    data: "created_at",
                    name: "created_at",
                    render: function (data) {
                        return data; // Now formatted in PHP before sending to the client
                    },
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (row.role === "superadmin") {
                            return "";
                        } else {
                            return `<i class="fa fa-edit editUser" data-id="${row.id}" title="Edit User" style="cursor: pointer;" title="Edit"></i>
                            <i class="fa fa-sign-in-alt" data-id="${row.id}" title="Login" style="cursor: pointer;" title="Login"></i>
                            `;
                        }
                    },
                },
            ],
            order: [[5, "desc"]], // Default sorting by created_at
        });


        $(document).on("change", ".verify", function() {
            var userId = $(this).data("id");
            var isVerify = $(this).prop("checked");
            var spinner = $('<div class="spinner-border d-none" role="status" style="width: 15px; height: 15px; margin: 0 0 0 7px;"><span class="visually-hidden">Loading...</span></div>');
            $(this).after(spinner); // Add the spinner after the checkbox

            spinner.removeClass("d-none"); // Show the spinner

            $.ajax({
                url: "{{ route('updateVerify.ajax') }}",
                method: "GET",
                data: { is_verify: isVerify, userId: userId },
                success: function(response) {
                    if (response.success) {
                        $.NotificationApp.send(
                            "Update Verify",
                            response.message || "The verify user has been updated.",
                            "top-right",
                            "#5ba035",
                            "success"
                        );
                    } else {
                        $.NotificationApp.send(
                            "Update Verify Failed",
                            response.message || "The verify user not has been updated.",
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );
                    }
                },
                error: function(xhr) {
                    $.NotificationApp.send(
                        "Update Verify Error",
                        "An error occurred while updating the user.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                },
                complete: function() {
                    spinner.addClass("d-none"); // Hide the spinner after the ajax is done
                }
            });
        });


        $("#executeBulckAction").on("click", function(e) {
            e.preventDefault();

            let button = $(this); // Store reference to the button
            let originalText = button.html(); // Store original button text
            let action = $("#demo-foo-filter-status").val();
            let selectedUsers = $(".selectRow:checked").length;

            // Change button text to spinner
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...').prop("disabled", true);

            if (selectedUsers === 0) {
            $.NotificationApp.send(
                "No User Selected",
                "Please select at least one user to delete.",
                "top-right",
                "rgba(0,0,0,0.2)",
                "error"
            );
            button.html(originalText).prop("disabled", false); // Restore button text
            } else if (action === "delete") {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to delete the selected users?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let selectedIds = [];
                    $(".selectRow:checked").each(function() {
                        selectedIds.push($(this).data("id"));
                    });
                    $.ajax({
                        url: "{{ route('user.delete') }}",
                        method: "POST",
                        data: { ids: selectedIds, _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Delete User", response.message || "The selected users have been deleted.", "success");
                                $table.ajax.reload(); // Update the data in the DataTable
                            } else {
                                Swal.fire("Delete User Failed", response.message || "An error occurred while deleting the users.", "error");
                            }
                            button.html(originalText).prop("disabled", false); // Restore button text
                        },
                        error: function(xhr) {
                            Swal.fire("Error", "An error occurred while deleting the users.", "error");
                            button.html(originalText).prop("disabled", false); // Restore button text
                        }
                    });
                } else {
                    button.html(originalText).prop("disabled", false); // Restore button if cancelled
                }
            });
            } else {
            Swal.fire({
                title: "No action selected",
                html: "Please select a bulk action before executing.",
                icon: "info",
                confirmButtonText: "OK"
            }).then(() => {
                button.html(originalText).prop("disabled", false); // Restore button text after alert
            });
            }
        });

        $(document).on("click", ".fa-sign-in-alt", function() {
            var userId = $(this).data("id");
            $.ajax({
                url: "{{ route('user.loginAjax') }}",
                method: "POST",
                data: { userId: userId, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        $.NotificationApp.send(
                            "Login Success",
                            response.message || "User logged in successfully.",
                            "top-right",
                            "#5ba035",
                            "success"
                        );
                        window.location.href = "/"; // Redirect to the home page
                    } else {
                        $.NotificationApp.send(
                            "Login Failed",
                            response.message || "An error occurred while logging in.",
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );
                    }
                },
                error: function(xhr) {
                    $.NotificationApp.send(
                        "Login Error",
                        "An error occurred while logging in.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                }
            });
        });



        $(document).on("click", ".editUser", function() {
            let userId = $(this).data("id");
            // Open the modal here
            // Add your code to open the modal
            $("#editUserModal").modal("show");

            $("#user_id").val(userId);

            $('#userDetails').html('<div class="spinner-border text-primary" style="margin: 0 auto !important; display: block;" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Ajax request to get the HTML of the user details
            $.ajax({
                url: "{{ route('user.formDetail') }}",
                method: "POST",
                data: { userId: userId, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        // Update the modal content with the user details HTML
                        $("#userDetails").html(response.html);
                        $('#urlRoute').val("{{ route('list.store') }}");
                    } else {
                        $.NotificationApp.send(
                            "error"
                        );
                    }
                },
                error: function(xhr) {
                    $.NotificationApp.send(
                        "Error",
                        "An error occurred while fetching user details.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                }
            });
        });

        $(document).on("click", "#addNewUser", function() {
            // Open the modal here
            // Add your code to open the modal
            $("#editUserModal").modal("show");

            $('#userDetails').html('<div class="spinner-border text-primary" style="margin: 0 auto !important; display: block;" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Ajax request to get the HTML of the user details
            $.ajax({
                url: "{{ route('user.formDetail') }}",
                method: "POST",
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        // Update the modal content with the user details HTML
                        $("#userDetails").html(response.html);
                        $('#urlRoute').val("{{ route('user.storeNew') }}");
                    } else {
                        $.NotificationApp.send(
                            "error"
                        );
                    }
                },
                error: function(xhr) {
                    $.NotificationApp.send(
                        "Error",
                        "An error occurred while fetching user details.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                }
            });
        });



        // Select all checkboxes when the #selectAll checkbox is clicked
        $("#selectAll").on("change", function () {
            $(".selectRow").prop("checked", $(this).prop("checked"));
        });

        // If any individual checkbox is unchecked, uncheck #selectAll
        $(".selectRow").on("change", function () {
            if ($(".selectRow:checked").length === $(".selectRow").length) {
                $("#selectAll").prop("checked", true);
            } else {
                $("#selectAll").prop("checked", false);
            }
        });
    });
</script>
@endsection
