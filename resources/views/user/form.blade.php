<form id="editUserForm">
    @csrf
    <input type="hidden" name="user_id" id="user_id">

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $user->name }}" id="name">
        <span class="text-danger error-name"></span>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ $user->email }}" id="email">
        <span class="text-danger error-email"></span>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" name="role" id="role">
            <option value="">Select Role</option>
            @foreach($roles as $key => $role)
                <option value="{{ $key }}" @if(!empty($user->role) && $user->role->id == $key) selected @endif>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <span class="text-danger error-role"></span>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control" placeholder="Enter your password" name="password">
        </div>
        <span class="text-danger error-password"></span>
    </div>
    <div class="mb-3">
        <label for="confirm" class="form-label">Confirm Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="confirm" class="form-control" placeholder="Enter your password" name="confirm">
        </div>
        <span class="text-danger error-confirm"></span>
    </div>

    <input type="hidden" value="{{ $user->id }}" name="user_id" id="user_id">
    <input type="hidden" name="url" id="urlRoute">
    <button class="btn btn-primary mt-2" id="submitUserForm" style="width: 100%;">Save</button>

    <div class="alert alert-danger mt-3 d-none" id="edit-user-error"></div>
</form>

<script>
    $("#submitUserForm").on("click", function(e) {
        e.preventDefault();
        let formData = $('#editUserForm').serialize();
        let button = $(this);
        let originalText = button.html(); // Store original button text
        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...').prop("disabled", true);
        let url = $("#urlRoute").val();
        // Clear previous error messages
        $(".text-danger").html("");

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $.NotificationApp.send(
                        "Success",
                        response.message || "User data update successfully.",
                        "top-right",
                        "#5ba035",
                        "success"
                    );
                    $('#userListTable').DataTable().ajax.reload();
                    $("#editUserModal").modal("hide"); // Hide the modal after successful save
                } else {
                    $.NotificationApp.send(
                        "Error",
                        response.message || "An error occurred while saving user data.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                }
                button.html(originalText).prop("disabled", false);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(".error-" + key).html(value[0]);
                    });
                } else {
                    $.NotificationApp.send(
                        "Error",
                        "An error occurred while saving user data.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                }
                button.html(originalText).prop("disabled", false);
            }
        });
    });
</script>
