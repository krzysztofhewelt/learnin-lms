import { createStore } from 'vuex';
import login from '@/store/modules/login';
import course from '@/store/modules/course';
import task from '@/store/modules/task';
import mark from '@/store/modules/mark';
import user from '@/store/modules/user';
import admin from '@/store/modules/admin';
import locale from '@/store/modules/locale';

const store = createStore({
	modules: {
		login,
		course,
		task,
		mark,
		user,
		admin,
		locale
	}
});

export default store;
