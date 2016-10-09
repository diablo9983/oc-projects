var clicked = false, originLeft, nodeLeft
$(document).ready(function() {
  initGroups()

  $(window).on('shown.oc.popup', function(e) {
    var popup = $(e.relatedTarget)
    if(popup.find('#groups-order').length > 0) {
      initOrderGroups()
    }
    if(popup.find('#editTask').length > 0) {
      $('#assignee').select2({
        placeholder: 'Pick an user',
        width: 'auto',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
          delay: 300,
          transport: function(params, success, error) {
            $.request('onGetUsers', {
              data: {
                query: params.data.term,
                project: $('#tasks-groups').data('project-id')
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

      var progress = $('#progress')[0],
      startValue = $('#progress-value').text()
      noUiSlider.create(progress, {
        start: parseInt(startValue),
        step: 1,
        connect: [true, false],
        range: {
          'min': 0,
          'max': 100
        }
      })

      progress.noUiSlider.on('slide', function() {
        var value = progress.noUiSlider.get().replace('.00','')
        $('input[type="hidden"][name="progress"]').val(value)
        $('#progress-value').text(value)
      })
    }
  }).on('hidden.oc.popup', function(e) {
    var popup = $(e.relatedTarget)
    if(popup.find('#groups-order').length > 0) {
      $('#groups-order').sortable('destroy')
    }
    if(popup.find('#editTask').length > 0) {
      var progress = $('#progress')[0]
      progress.noUiSlider.off()
      progress.noUiSlider.destroy()
    }
  })
  $('#tasks-groups').on('ajaxUpdate', function() {
    initGroups()
    $('.loading .loading-indicator-container').hide()
  }).on('mousedown','.tasks-groups', function(e) {
    if($(e.target).is('.menu') ||
      $(e.target).parents('.menu').length > 0 ||
      $(e.target).is('.task') ||
      $(e.target).parents('.task').length > 0) return

    clicked = true
    originLeft = e.pageX
    nodeLeft = $(this).scrollLeft()
    $('.tasks-groups').addClass('moving')
  }).on('mousemove', function(e) {
    if(!clicked) return

    $('.tasks-groups').scrollLeft(nodeLeft + (originLeft - e.pageX))
  }).on('mouseup', function() {
    clicked = false
    $('.tasks-groups').removeClass('moving')
  })

  $(document).on('change','input[name="color"]', function() {
    var color = $(this).val(),
        old_color = $('.ico-chooser').attr('class').replace('ico-chooser ', '')

    $('.ico-chooser').toggleClass(color + ' ' + old_color)
  })

  $(document).on('click', '#groups-order .edit-group', function() {
    $(this).closest('.control-popup').trigger('close.oc.popup')
  })

  $(document).on('click', '#date .clear', function() {
    $('#date').datePicker('emptyValues')
  })

  $(window).on('resize', setTasksHeight)
  setTasksHeight()
})

function setTasksHeight()
{
  var h = $(window).height() - ($('#layout-mainmenu').outerHeight() + $('.control-toolbar').not('#layout-mainmenu').outerHeight() + $('.control-breadcrumb').outerHeight())
  $('.tasks-groups').css({height: h + 'px'})
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
    handle: '.handle',
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

function saveTask(el, data, update)
{
  update = update || true;
  if(update) {
    $('#tasks-'+data.group).append(data.task)
    updatePlaceholder($('#tasks-'+data.group))
  }
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup')
  }
}

function addGroup(el)
{
  reloadGroups()
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup')
  }
}

function hideGroup(data,fromPopup)
{
  reloadGroups()
  if(fromPopup) {
    var li = $('.list-group').find('li[data-id="' + data.id + '"]')
    li.removeClass('group-hidden');
    li.find('a.show-group').tooltip('hide')
    li.find('a.show-group').remove()
  }
}
