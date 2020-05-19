import Vue from 'vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {faTimesCircle, faCheckCircle} from "@fortawesome/free-regular-svg-icons";
import {faSyncAlt, faCircleNotch} from "@fortawesome/free-solid-svg-icons";
import {faGithub} from "@fortawesome/free-brands-svg-icons";
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

library.add(faTimesCircle)
library.add(faCheckCircle)
library.add(faGithub)
library.add(faSyncAlt)
library.add(faCircleNotch)

Vue.component('font-awesome-icon', FontAwesomeIcon)
