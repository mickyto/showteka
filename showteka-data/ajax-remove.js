jQuery(document).ready( function($) {
  $(".ajax-link").click( function() {
    var data = {
      action: 'test_response',
      post_var: 'Удалено',
      id: this.rel
    };
    if (this.id) {
      data.key = this.id;
    }

    _this = this;
    // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
    $.post(the_ajax_script.ajaxurl, data, function(response) {
      response = JSON.parse(response);
      if (response.action == 'remove-offer') {
        $("#" + _this.rel + _this.id).remove();
      }
      if (response.action == 'remove-event') {
        $("#sh-section-" + _this.rel).remove();
        $('#' + _this.rel).attr('checked', false);
      }
      console.log(response)
      console.log("#sh-section-" + _this.rel)

      alert(response.msg);
    });
    return false;
  });
});
