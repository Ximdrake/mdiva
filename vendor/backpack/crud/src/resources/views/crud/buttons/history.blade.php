@if ($crud->hasAccess('delete', $entry))
	<a href="{{ '/admin/user-history/'.$entry->getKey() }}" class="btn btn-sm btn-link"><span><i class="la la-history"></i> History</span></a>
@endif

@push('after_scripts') @if (request()->ajax()) @endpush @endif
@bassetBlock('backpack/crud/buttons/delete-button-'.app()->getLocale().'.js')
<script>

	if (typeof sendEntry != 'function') {
	  $("[data-button-type=send]").unbind('click');

	  function sendEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');
		console.log(route);
		swal({
		  title: "Send Alert",
		  text: "Trigger alarm to the patient",
		  icon: "warning",
		  buttons: ["{!! trans('backpack::crud.cancel') !!}", "Alert"],
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      success: function(result) {
					console.log(result)
			          if (result.success) {
						  // Redraw the table
						  if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
							  // Move to previous page in case of deleting the only item in table
							  if(crud.table.rows().count() === 1) {
							    crud.table.page("previous");
							  }

							  crud.table.draw(false);
						  }

			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: result.message,
							layout: 'bottomRight', // Position of the notification
							timeout: 3000, // Duration before auto-closing (in milliseconds)
							progressBar: true, // Show a progress bar
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');
			          }  

			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
                        text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	buttons: false,
		              });
			      }
			  });
			}
		});


      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@endBassetBlock
@if (!request()->ajax()) @endpush @endif
