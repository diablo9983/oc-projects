$(document).ready(function() {

  $(window).on('show.oc.popup', function(e) {
    var items = [], selected = []
    if($('#fill-selectize').length > 0) {
      items = $.parseJSON($('#fill-selectize').text());
      $.each(items,function(index, value) {
        selected.push(value.id)
      })
      $('#fill-selectize').remove()
    }
    $('#team').selectize({
      plugins: ['remove_button'],
      persist: false,
      valueField: 'id',
      labelField: 'name',
      searchField: 'name',
      sortField: 'name',
      hideSelected: true,
      highlight: false,
      options: items,
      items: selected,
      create: false,
      load: function(query, callback) {
        if (!query.length) return callback();
        $.request('onGetUsers', {
          data: {
            query: query
          },
          success: function(data) {
            callback(data)
          }
        })
      }
    })

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
