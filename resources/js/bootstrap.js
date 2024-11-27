import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Bootstrap and Popper.js imports
import "bootstrap";
// import "@popperjs/core";
