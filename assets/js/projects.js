$(document).ready(function() {

  $(window).on('show.oc.popup', function(e) {
    $('#team').select2({
      placeholder: 'Pick an user',
      width: 'auto',
      minimumInputLength: 3,
      ajax: {
        delay: 300,
        transport: function(params, success, error) {
          $.request('onGetUsers', {
            data: {
              query: params.data.term
            },
            success: success,
            error: error
          })
        },
        processResults: function(data) {
          return {
            results: $.map(data, function(el) { return el })
          }
        }
      }
    })
  }).on('close.oc.popup', function() {
    $('#team').select2('destroy')
  })

})

function refreshProjects(el)
{
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup')
  }
  $.request('onGetProjectList', {
    update: {projects: '#projects'},
    beforeUpdate: function() {
      $('.loading-overlay').show()
    },
    complete: function() {
      $('.loading-overlay').hide()
    }
  })
}
