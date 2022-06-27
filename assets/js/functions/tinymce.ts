import tinymce from "tinymce";
import "tinymce/icons/default";
import "tinymce/themes/silver";
import "tinymce/models/dom";
import "tinymce/plugins/advlist";
import "tinymce/plugins/code";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/emoticons/js/emojis";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/table";

function initTinyMCE(textareaId: string) {
  return tinymce.init({
    selector: textareaId,
    plugins: "advlist code emoticons link lists table",
    toolbar: "bold italic | bullist numlist | link emoticons",
    toolbar_mode: "floating",
    tinycomments_mode: "embedded",
    tinycomments_author: "Marius Bjercke",
  });
}

export { initTinyMCE };
