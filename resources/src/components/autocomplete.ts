import { metaphone } from "metaphone";
import { AlpineComponent } from "alpinejs";

interface AutocompleteData {
  show: boolean;
  options: string[];
  query: string;
  phonemesOptions: string[];
  selected: string;
  filteredOptions: string[];
  selectedIndex: number;
  container: any;
  input: any;
  select(n: number): void;
}

export default (optionId: string): AlpineComponent<AutocompleteData> => ({
  show: false,
  options: [] as string[],
  selected: "",
  selectedIndex: 0,
  query: "",

  init() {
    const jsonEl = document.getElementById(optionId);
    if (jsonEl) {
      this.options = JSON.parse(jsonEl.textContent ?? "");
    }
    this.selectedIndex = 0;

    this.$watch("query", () => {
      this.selectedIndex = 0;
    });

    this.$watch("show", (s: boolean) => {
      if (!s) {
        this.query = this.selected;
      }
    });
  },

  get phonemesOptions(): string[] {
    return this.options.map(metaphone);
  },

  get filteredOptions(): string[] {
    return this.options.filter(
      (opt, i) =>
        opt.toLowerCase().includes(this.query.toLowerCase()) ||
        this.phonemesOptions[i].includes(metaphone(this.query)),
    );
  },

  select(n: number) {
    this.selectedIndex = n;
    this.selected = this.filteredOptions[n];
    this.query = this.selected;
    this.show = false;
    this.$refs.input.blur;
  },

  container: {
    ["@click.outside"]() {
      this.show = false;
    },
  } as any,

  input: {
    ["@focus"]() {
      this.show = true;
    },
    ["@keydown.tab.prevent"]() {
      this.query = this.filteredOptions[this.selectedIndex];
    },
    ["@keydown.enter.prevent"]() {
      this.selected = this.filteredOptions[this.selectedIndex];
      this.show = false;
      this.$el.blur();
    },
    ["@keydown.arrow-down.prevent"]() {
      const max = this.filteredOptions.length;
      this.selectedIndex = Math.min(max, this.selectedIndex + 1);
    },
    ["@keydown.arrow-up.prevent"]() {
      this.selectedIndex = Math.max(0, this.selectedIndex - 1);
    },
  } as any,
});
