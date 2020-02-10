import store from "../store";
import axios from "axios/index";
import { apiJoin } from "../utils/path";

export default function(route, method, data, isSecure = false) {
  let request =
    "GET" === method
      ? axios.get(route, { params: data })
      : axios.request({
          url: apiJoin(route),
          method: method,
          data: data
        });

  if (isSecure) {
    request.headers.add({
      Authorization: "Bearer " + store.getters.jwt
    });
  }

  return request;
}
