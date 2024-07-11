export default <T>(endpoint: string, interval = 500) => ({
  data: null as T | null,

  init() {
    this.fetchData();
    setInterval(() => this.fetchData(), interval);
  },

  fetchData() {
    fetch(endpoint)
      .then((resp) => resp.json())
      .then((data) => (this.data = data));
  },
});
