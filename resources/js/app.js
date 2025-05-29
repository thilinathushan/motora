import "./bootstrap";
import "../scss/colors.scss";
import "@flaticon/flaticon-uicons/css/brands/all.css";
import "@flaticon/flaticon-uicons/css/regular/all.css";
import "@flaticon/flaticon-uicons/css/solid/all.css";
import MetaMaskHandler from "./re_auth/metamask";

if (typeof window.metaMaskHandler === 'undefined') {
    window.metaMaskHandler = new MetaMaskHandler();
}
