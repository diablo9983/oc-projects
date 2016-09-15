$(document).ready(function() {
  initGroups()

  $(window).on('shown.oc.popup', function(e) {
    var popup = $(e.relatedTarget)
    if(popup.find('#groups-order').length > 0) {
      initOrderGroups()
    }
  }).on('hidden.oc.popup', function(e) {
    var popup = $(e.relatedTarget)
    if(popup.find('#groups-order').length > 0) {
      $('#groups-order').sortable('destroy')
    }
  })
  $('#tasks-groups').on('ajaxUpdate', function() {
    initGroups()
    $('.loading .loading-indicator-container').hide()
  })
})

function initGroups()
{
  $('.tasks-group-content').each(function(i, el) {
    $(el).sortable({
      group: 'tasks',
      ghostClass: 'task-placeholder',
      filter: '.task-placeholder-empty',
      onSort: function(event) {
        $.request('onTasksSave', {
          data: {
            group: $(event.target).data('group-id'),
            tasks: $(event.target).sortable('toArray').filter(function(e){ return e !== '0' })
          }
        })
        updatePlaceholder(event.target)
      }
    })
  })
}
function destroyGroups()
{
  $('.tasks-group-content').each(function(i, el) {
    $(el).sortable('destroy')
  })
}
function initOrderGroups()
{
  $('#groups-order').sortable({
    ghostClass: 'list-group-placeholder',
    onSort: function(event) {
      $('.loading .loading-indicator-container').show()
      $.request('onOrderGroups', {
        data: {
          groups: $(event.target).sortable('toArray')
        },
        success: function() {
          $.request('onGetGroups', {
            data: {
              id: $('#tasks-groups').data('project-id')
            },
            beforeUpdate: destroyGroups,
            update: {'groups':'#tasks-groups'}
          })
        }
      })
    }
  })
}

function updatePlaceholder(list)
{
  list = $(list)
  if(list.find('.task').length > 0) {
    list.children('.task-placeholder-empty').remove()
    list.removeClass('tasks-group-content-empty')
  } else {
    list.append('<div class="task-placeholder-empty" data-id="0"></div>')
    list.addClass('tasks-group-content-empty')
  }
}

function addTask(el, data)
{
  $('#tasks-'+data.group).append(data.task)
  updatePlaceholder($('#tasks-'+data.group))
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup')
  }
}
