$(document).ready(function() {

  $('.tasks-group-content').each(function(i, el) {
    $(el).sortable({
      group: 'tasks',
      ghostClass: 'task-placeholder',
      filter: '.task-placeholder-empty',
      onAdd: onAdd,
      onUpdate: onUpdate,
      onRemove: onRemove
    })
  })

})

function onAdd(event)
{
  updatePlaceholder(event)
}

function onUpdate(event)
{
  console.log('update')
}

function onRemove(event)
{
  updatePlaceholder(event)
}

function updatePlaceholder(list)
{
  list = $(list.target);
  if(list.find('.task').length > 0) {
    list.children('.task-placeholder-empty').remove()
    list.removeClass('tasks-group-content-empty')
  } else {
    list.append('<div class="task-placeholder-empty"></div>')
    list.addClass('tasks-group-content-empty')
  }
}
