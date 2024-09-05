import $ from 'jquery'

/**
 * Sends an Ajax request and handles the response.
 * @param {Object} $form - jQuery object of the form.
 * @param {Object} $popup - jQuery object of the popup.
 */
function CelticAjaxCheckCode (celticCode, eventID) {
  const ajaxData = {
    code: celticCode,
    event_id: eventID
  }

  let response = false

  $.ajax({
    url: customjs_ajax_object.ajax_url,
    type: 'post',
    async: false,
    data: {
      action: 'celtic_check_code',
      ajax_nonce: customjs_ajax_object.ajax_nonce,
      data: ajaxData
    },
    success: function (response) {
      response = response
    },
    error: function (xhr) {
      response = xhr?.responseJSON
    }
  })

  return response
}

export { CelticAjaxCheckCode }
