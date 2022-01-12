<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Todo List </title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
 
 <style>
   .container{
    padding: 0.5%;
   } 
</style>
</head>
<body>
 
<div class="container">
    <h2 style="margin-top: 12px;" class="alert alert-success">Todo List Module </h2><br>
    <div class="row">
        <div class="col-12">
          <a href="javascript:void(0)" class="btn btn-success mb-2" id="create-new-task">Add Subject</a> 
          
          <table class="table table-bordered" id="laravel_crud">
           <thead>
              <tr>
                 <th>Id</th>
                 <th>Subject</th>
                 <th>Current Date</th>
                 <td colspan="2">Action</td>
              </tr>
           </thead>
           <tbody id="tasks-crud">
              @foreach($task as $t)
              <tr id="task_id_{{ $t->id }}">
                 <td>{{ $t->id  }}</td>
                 <td>{{ $t->subject }}</td>
                 <td>{{ $t->current_date }}</td>
                 <td><a href="javascript:void(0)" id="edit-task" data-id="{{ $t->id }}" class="btn btn-info">Edit</a></td>
                 <td>
                  <a href="javascript:void(0)" id="delete-task" data-id="{{ $t->id }}" class="btn btn-danger delete-task">Delete</a></td>
              </tr>
              @endforeach
           </tbody>
          </table>
          
       </div> 
    </div>
</div>
<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="taskCrudModal"></h4>
    </div>
    <div class="modal-body">
        <form id="taskForm" name="taskForm" class="form-horizontal">
           <input type="hidden" name="task_id" id="task_id">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Subject</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="subject" name="subject" value="" placeholder="Enter Subject" required="">
                </div>
            </div>
 
            <div class="form-group">
                <label class="col-sm-2 control-label">Current Date</label>
                <div class="col-sm-12">
                <?php $ldate = date('Y-m-d');?>
                    <input class="form-control" id="body" name="current_date" value="{{$ldate}}" placeholder="YYYY-MM-DD" required="">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save
             </button>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        
    </div>
</div>
</div>
</div>
</body>
</html>
<script>
  $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#create-new-task').click(function () {
        $('#btn-save').val("create-task");
        $('#taskForm').trigger("reset");
        $('#taskCrudModal').html("Add New Subject");
        $('#ajax-crud-modal').modal('show');
    });
 
    $('body').on('click', '#edit-task', function () {
      var task_id = $(this).data('id');
      $.get('todo_list/'+task_id+'/edit', function (data) {
         $('#taskCrudModal').html("Edit Subject");
          $('#btn-save').val("edit-task");
          $('#ajax-crud-modal').modal('show');
          $('#task_id').val(data.id);
          $('#subject').val(data.subject);
          $('#current_date').val(data.current_date);  
      })
   });
    $('body').on('click', '.delete-task', function () {
        var task_id = $(this).data("id");
        confirm("Are You sure want to delete !");
 
        $.ajax({
            type: "DELETE",
            url: "{{ url('todo_list')}}"+'/'+task_id,
            success: function (data) {
                $("#task_id_" + task_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });   
  });
 
 if ($("#taskForm").length > 0) {
      $("#taskForm").validate({
 
     submitHandler: function(form) {
      var actionType = $('#btn-save').val();
      $('#btn-save').html('Sending..');
      
      $.ajax({
          data: $('#taskForm').serialize(),
          url: "{{ route('todo_list.store') }}",
          type: "post",
          dataType: 'json',
          success: function (data) {
              var task = '<tr id="task_id_' + data.id + '"><td>' + data.id + '</td><td>' + data.subject + '</td><td>' + data.current_date + '</td>';
              task += '<td><a href="javascript:void(0)" id="edit-task" data-id="' + data.id + '" class="btn btn-info">Edit</a></td>';
              task += '<td><a href="javascript:void(0)" id="delete-task" data-id="' + data.id + '" class="btn btn-danger delete-task">Delete</a></td></tr>';
               
              
              if (actionType == "create-task") {
                  $('#tasks-crud').prepend(task);
              } else {
                  $("#task_id_" + data.id).replaceWith(task);
              }
 
              $('#taskForm').trigger("reset");
              $('#ajax-crud-modal').modal('hide');
              $('#btn-save').html('Save Changes');
              
          },
          error: function (data) {
              console.log('Error:', data);
              $('#btn-save').html('Save Changes');
          }
      });
    }
  })
}
   
  
</script>