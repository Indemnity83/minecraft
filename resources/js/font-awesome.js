import Vue from 'vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {faSyncAlt, faCircleNotch, faExclamationTriangle, faTimesCircle, faCheckCircle} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

library.add(faTimesCircle)
library.add(faCheckCircle)
library.add(faSyncAlt)
library.add(faCircleNotch)
library.add(faExclamationTriangle)

Vue.component('font-awesome-icon', FontAwesomeIcon)
