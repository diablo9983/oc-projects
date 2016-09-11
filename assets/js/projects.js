(function() {

  refreshProjects();

})($)

function refreshProjects(el)
{
  if(typeof el !== 'undefined') {
    $(el).parents('.modal').trigger('close.oc.popup');
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
