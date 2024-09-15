
<div class="container mt-5">
    <h2 class="mb-4">Item List</h2>
    <a href="javascript:void(0)" id="addNewItem" class="btn btn-success mb-2">+ Add New Item</a>

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal for Add/Edit Item -->
<div class="modal fade" id="itemModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <input type="hidden" id="itemId">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveBtn"></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // Initialize DataTable
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('items.index') }}", // Fetch the data using AJAX
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'}, // Auto-incrementing index
                {data: 'name', name: 'name'}, // Item name
                {data: 'description', name: 'description'}, // Item description
                {data: 'action', name: 'action', orderable: false, searchable: false}, // Action buttons (Edit/Delete)
            ]
        });

        // Add new item button
        $('#addNewItem').click(function () {
            $('#itemForm').trigger("reset");
            $('#modalHeading').html("Add New Item");
            $('#saveBtn').html("Save");
            $('#itemModal').modal('show');
            $('#itemId').val('');
        });

        // Save or Update Item
        $('#itemForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('items.store') }}", // Store or update data
                type: "POST",
                data: {
                    id: $('#itemId').val(),
                    name: $('#name').val(),
                    description: $('#description').val(),
                    _token: '{{ csrf_token() }}', // CSRF token
                },
                success: function (response) {
                    $('#itemModal').modal('hide');
                    table.ajax.reload(); // Reload the DataTable dynamically
                    alert(response.success);
                }
            });
        });

        // Edit item
        $('body').on('click', '.editItem', function () {
            var id = $(this).data('id');
            $.get("{{ route('items.index') }}" + '/' + id + '/edit', function (data) {
                $('#modalHeading').html("Edit Item");
                $('#saveBtn').html("Update");
                $('#itemModal').modal('show');
                $('#itemId').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
            });
        });

        // Delete item
        $('body').on('click', '.deleteItem', function () {
            var id = $(this).data('id');
            if (confirm("Are you sure want to delete?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('items.store') }}" + '/' + id,
                    data: {_token: '{{ csrf_token() }}'}, // CSRF token
                    success: function (response) {
                        table.ajax.reload(); // Reload DataTable after deletion
                        alert(response.success);
                    }
                });
            }
        });
    });
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>


