$(document).ready(function() {

  $('.tasks-group-content').each(function(i, el) {
    $(el).sortable({
      group: 'tasks',
      ghostClass: 'task-placeholder',
      filter: '.task-placeholder-empty',
      onSort: onSort
    })
  })

})

function onSort(event)
{
  onTasksSave($(event.target).data('group-id'),$(event.target).sortable('toArray').filter(function(e){ return e !== '0' }))
  updatePlaceholder(event.target)
}

function onTasksSave(group,tasks)
{
  $.request('onTasksSave', {
    data: {
      group: group,
      tasks: tasks
    }
  });
}

function updatePlaceholder(list)
{
  list = $(list);
  if(list.find('.task').length > 0) {
    list.children('.task-placeholder-empty').remove()
    list.removeClass('tasks-group-content-empty')
  } else {
    list.append('<div class="task-placeholder-empty" data-id="0"></div>')
    list.addClass('tasks-group-content-empty')
  }
}
