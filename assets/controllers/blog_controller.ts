import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["title", "content", "newPost"];

  titleTarget: HTMLInputElement;
  contentTarget: HTMLInputElement;
  newPostTarget: HTMLDivElement;

  newPost() {
    this.newPostTarget.style.display = "block";
  }

  submit() {
    console.log("Target: " + this.titleTarget.value + this.contentTarget.value);
  }
}
