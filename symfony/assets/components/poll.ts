import { AlpineComponent } from "alpinejs";

interface PollData<T> {
  data: T | null;
}

export default <T>(
  endpoint: string,
  interval = 500,
): AlpineComponent<PollData<T>> => ({
  data: null as T | null,

  init() {
    const fetchData = () => {
      fetch(endpoint)
        .then((resp) => resp.json())
        .then((data) => (this.data = data));
    };

    fetchData();
    setInterval(() => {
      fetchData();
    }, interval);
  },
});
