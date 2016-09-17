var clicked = false, originLeft, nodeLeft
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
  }).on('mousedown','.tasks-groups', function(e) {
    if(!$(e.target).is('.tasks-groups')) return

    clicked = true
    originLeft = e.pageX
    nodeLeft = $(this).scrollLeft()
    $('.tasks-groups').addClass('moving')
  }).on('mousemove', function(e) {
    if(!clicked) return;

    $('.tasks-groups').scrollLeft(nodeLeft + (originLeft - e.pageX))
  }).on('mouseup', function() {
    clicked = false
    $('.tasks-groups').removeClass('moving')
  })

  $(window).on('resize', setTasksHeight)
  setTasksHeight()
})

function setTasksHeight()
{
  var h = $(window).height() - ($('#layout-mainmenu').height() + $('.control-toolbar').not('#layout-mainmenu').height() + $('.control-breadcrumb').height() + 135)
  console.log(h)
  $('.tasks-group-content').css({maxHeight: h + 'px'})
}

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
        success: reloadGroups
      })
    }
  })
}

function reloadGroups()
{
  $.request('onGetGroups', {
    data: {
      id: $('#tasks-groups').data('project-id')
    },
    beforeUpdate: destroyGroups,
    update: {'groups':'#tasks-groups'}
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

function addGroup(el, data)
{
  reloadGroups()
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup')
  }
}
