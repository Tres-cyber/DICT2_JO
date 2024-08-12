import { AlpineComponent } from "alpinejs";
import { Toast } from "bootstrap";

interface NoticationData {
  toast: Toast | null;
}

export default (): AlpineComponent<NoticationData> => ({
  toast: null,

  init() {
    this.toast = new Toast(this.$el);
    this.toast.show();
  },
});
