import { Controller } from "@hotwired/stimulus";
import tinymce from "@assets/js/functions/tinymce";

export default class extends Controller {
  static targets = ["title", "content", "newPost", "postsContainer"];
  private editor: any;

  titleTarget: HTMLInputElement;
  contentTarget: any;
  newPostTarget: HTMLDivElement;
  postsContainerTarget: HTMLDivElement;

  connect() {
    this.editor = tinymce.init({
      target: this.contentTarget,
      content_css: "/build/wysiwyg.css",
      plugins: "advlist code emoticons link lists table",
      toolbar: "bold italic | bullist numlist | link emoticons",
      toolbar_mode: "floating",
      width: "100%",
    });
    console.log(this.contentTarget);
  }

  newPost() {
    this.newPostTarget.classList.add("blog__new-post_show");
  }

  async submit(e) {
    e.preventDefault();

    const content = tinymce.activeEditor.getContent();
    const form = this.newPostTarget.querySelector("form") as HTMLFormElement;

    // Update the form with the new TinyMCE content
    this.contentTarget.value = content;

    const formData = new FormData(form);

    // Use X-Requested-With: XMLHttpRequest to prevent CSRF
    const response = await fetch("/blog/post/new", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }

    if (data.success) {
      this.addPlaceholderPost(data.result);

      // Reset the form
      this.titleTarget.value = "";
      tinymce.activeEditor.setContent("");

      this.close();
    }
  }

  close() {
    this.newPostTarget.classList.remove("blog__new-post_show");
  }

  addPlaceholderPost(result) {
    const dummy = this.postsContainerTarget.querySelectorAll(".blog__post")[0] as HTMLDivElement;
    const post = dummy.cloneNode(true) as HTMLDivElement;
    const noPostsYetMessage = this.postsContainerTarget.querySelector(".blog__no-posts-yet");
    post.classList.remove("blog__post_hidden");
    if (post.hasAttribute("data-blog-target")) {
      post.removeAttribute("data-blog-target");
    }
    post.querySelector(".blog__post-title").innerHTML = result.title;
    post.querySelector(".blog__post-content").innerHTML = result.content;
    post.querySelector(".blog__post-author").innerHTML = result.author;

    // Remove the no posts yet message if it exists
    if (noPostsYetMessage) {
      noPostsYetMessage.remove();
    }

    // Add the new post to top of the list
    this.postsContainerTarget.prepend(post);
  }
}
