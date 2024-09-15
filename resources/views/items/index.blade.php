<div class="container mt-5">
  <button id="addNewItem" class="btn btn-success mb-3">Add New</button>

  <table id="itemsTable" class="table table-bordered">
      <thead>
          <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Action</th>
          </tr>
      </thead>
  </table>

  <!-- Modal for Add/Edit Item -->
  <div class="modal fade" id="itemModal" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalTitle">Add New Item</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <form id="itemForm">
                      <input type="hidden" id="itemId">
                      <div class="mb-3">
                          <label for="name" class="form-label">Name</label>
                          <input type="text" id="name" class="form-control">
                      </div>
                      <div class="mb-3">
                          <label for="description" class="form-label">Description</label>
                          <textarea id="description" class="form-control"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Save</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>

<!-- Include jQuery, Bootstrap JS, DataTables, and AJAX -->
<script>
$(document).ready(function() {
  var table = $('#itemsTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('items.index') }}",
      columns: [
          {data: 'name', name: 'name'},
          {data: 'description', name: 'description'},
          {data: 'action', name: 'action', orderable: false, searchable: false}
      ]
  });

  $('#addNewItem').click(function() {
      $('#itemForm')[0].reset();
      $('#modalTitle').text('Add New Item');
      $('#itemModal').modal('show');
  });

  $('#itemForm').on('submit', function(e) {
      e.preventDefault();
      var id = $('#itemId').val();
      var url = id ? "/items/" + id : "/items";
      var method = id ? 'PUT' : 'POST';

      $.ajax({
          url: url,
          method: method,
          data: {
              name: $('#name').val(),
              description: $('#description').val(),
              _token: "{{ csrf_token() }}"
          },
          success: function(response) {
              $('#itemModal').modal('hide');
              table.ajax.reload();
          }
      });
  });

  $('body').on('click', '.edit', function() {
      var id = $(this).data('id');
      $.get('/items/' + id + '/edit', function(data) {
          $('#modalTitle').text('Edit Item');
          $('#itemId').val(data.id);
          $('#name').val(data.name);
          $('#description').val(data.description);
          $('#itemModal').modal('show');
      });
  });

  $('body').on('click', '.delete', function() {
      var id = $(this).data('id');
      if (confirm('Are you sure to delete this item?')) {
          $.ajax({
              url: "/items/" + id,
              method: 'DELETE',
              data: {_token: "{{ csrf_token() }}"},
              success: function(response) {
                  table.ajax.reload();
              }
          });
      }
  });
});
</script>
