import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';  // sudah termasuk Popper.js
