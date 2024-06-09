import React from "react";

import ReactDOM from "react-dom";

import { App } from "./App";

const containers = document.querySelectorAll(".cdw-widget-container ");

containers.forEach((container) => {

ReactDOM.render(<App />, container);

});