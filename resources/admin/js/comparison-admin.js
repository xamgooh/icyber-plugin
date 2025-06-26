// Cherrypick default plugins
import Sortable, { AutoScroll } from "sortablejs/modular/sortable.core.esm.js";

(function ($) {
  "use strict";
  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(function () {
    // remove bold in header for admin panel
    /*if($("#com_comporison_metadata_description_ifr")){
      $("#com_comporison_metadata_description_ifr").contents().find("html body").children().attr("style","font-weight:normal !important;")
    }*/
    Sortable.mount(new AutoScroll());
    var el = $("#repeatable-fieldset-one tbody");
    el.each((i, el) => {

      const sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: "blue-background-class",
        // Changed sorting within list
        onUpdate: function (/**Event*/ evt) {
          let order = 1;
          $(evt.target)
          .find("tr:not(.empty-row)")
          .each(function (i, el) {
            $(el).find("td:first-child").html(order++);
          });
        }
      });
    });

    /** tabs */
    $(".com-tab-group li a").on("click", function (e) {
      e.preventDefault();

      let data_key = "." + $(this).data("key");

      $(".com-tab-group li.active").removeClass("active");
      $(this).parent().addClass("active");

      $(".metadata-wrap").addClass("hidden");
      $(data_key).removeClass("hidden");
    });

    /** color field */
    $(".color_field").each(function () {
      $(this).wpColorPicker({
        mode: "hsl",
        controls: {
          horiz: "s", // horizontal defaults to saturation
          vert: "l", // vertical defaults to lightness
          strip: "h" // right strip defaults to hue
        },
        palettes: ["#eee", "#F0544F", "#222", "#1E72BD", "#666", "#191c5c"]
      });
    });

    /** repeater field */
    $(".add-row").on("click", function () {
      let order = $(this).data("order");
      $(this).data("order", order + 1);
      let row = $(this)
        .parent("p")
        .siblings("table")
        .find("tbody tr.empty-row.screen-reader-text")
        .clone(true);
      row.removeClass("empty-row screen-reader-text");

      row.find("td:first-child").html(order);
      row.insertBefore(
        $(this).parent("p").siblings("table").find("tbody tr:last")
      );
      return false;
    });

    $(".remove-row").on("click", function () {
      let table = $(this).parents("table");
      let btn = $(table).siblings("p").find(".add-row");
      let order = 1;

      $(btn).data("order", $(btn).data("order") - 1);
      $(this).parents("tr").remove();

      $(table)
        .find("tbody tr:not(.empty-row)")
        .each(function (i, el) {

          $(el).find("td:first-child").html(order++);
        });

      return false;
    });

    /** validate field */
    let form = $("form[name='post']");
    $(form)
      .find("#publish")
      .click(function (e) {
        $(form).validate();
        if (
          $(form).valid() &&
          $("#com_comporison_metadata_amount").val() != "" &&
          $("#text_label_term_condition").val() != "" &&
          $("#com_comporison_metadata_rating").val() != "" &&
          $("#com_comporison_metadata_select_btn_link").val() != ""
        ) {
          $("#com_comporison_metabox_details").removeClass("error");
          return true;
        } else {
          $("#com_comporison_metabox_details").addClass("error");
          return false;
        }
      });

    $(".com_upload_image").on("click", function (e) {
      e.preventDefault();

      var com_upload_button = $(this);

      let frame;

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media.frames.customBackground = wp.media({
        title: "choose image",
        library: {
          type: "image"
        },
        button: {
          text: "Upload"
        },
        multyple: false
      });

      frame.on("select", function (e) {
        var attachment = frame.state().get("selection").first();

        // and show the image's data
        var image_id = attachment.id;

        var image_url = attachment.attributes.url;

        // pace an id
        com_upload_button.parent().find(".com_upload_image_save").val(image_id);

        // show an image
        com_upload_button
          .parent()
          .find(".com_upload_image_show")
          .attr("src", image_url);
        com_upload_button.parent().find(".com_upload_image_show").show();

        // show "remove button"
        com_upload_button.parent().find(".com_upload_image_remove").show();

        // hide "upload" button
        com_upload_button.hide();
      });
      frame.open();
    });
    // remove image
    $(".com_upload_image_remove").on("click", function (e) {
      var remove_button = $(this);

      e.preventDefault();

      // remove an id
      remove_button.parent().find(".com_upload_image_save").val("");

      // hide an image
      remove_button.parent().find(".com_upload_image_show").attr("src", "");
      remove_button.parent().find(".com_upload_image_show").hide();

      // show "Upload button"
      remove_button.parent().find(".com_upload_image").show();

      // hide "remove" button
      remove_button.hide();
    });
  });
})(jQuery);
