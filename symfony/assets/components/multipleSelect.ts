import { AlpineComponent } from "alpinejs";

type MultipleSelectedData = {
  checked: string[];
  permanent: string[];
  values: string[];
  remove(s: string): void;
  add(s: string): void;
};

export default (
  checkedId: string,
  permanentId: string,
): AlpineComponent<MultipleSelectedData> => ({
  checked: [],
  permanent: [],

  init() {
    let el = document.getElementById(checkedId);
    if (el) {
      this.checked = JSON.parse(el.textContent ?? "");
    }

    el = document.getElementById(permanentId);
    if (el) {
      this.permanent = JSON.parse(el.textContent ?? "");
    }
  },

  get values() {
    return this.permanent
      .concat(this.checked)
      .filter((v, i, s) => s.indexOf(v) === i);
  },

  remove(s: string) {
    this.checked = this.checked.filter((v) => v !== s);
  },

  add(s: string) {
    if (s === undefined || s.trim().length === 0) return;
    if (this.values.includes(s)) return;
    this.checked = [...this.checked, s];
  },
});
