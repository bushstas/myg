import axios from 'axios';

export class CommonService {
  fetch = (action, options) => {
    return axios
      .get(`/api/${this.name}/${action}.php`, {
        ...options,
      })
      .then(response => response.data.data)
      .catch(e => console.log(e));
  };
}
