
import LoginComponent from "../../components/frontend/auth/LoginComponent.vue";
import ForgetPasswordComponent from "../../components/frontend/auth/ForgetPasswordComponent.vue";
import VerifyEmailComponent from "../../components/frontend/auth/VerifyEmailComponent.vue";
import ResetPasswordComponent from "../../components/frontend/auth/ResetPasswordComponent.vue";
export default [
    {
        path: '/login',
        component: LoginComponent,
        name: 'auth.login',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password',
        component: ForgetPasswordComponent,
        name: 'auth.forgetPassword',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password/verify',
        name: 'auth.verifyEmail',
        component: VerifyEmailComponent,
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password/reset-password',
        name: 'auth.resetPassword',
        component: ResetPasswordComponent,
        meta: {
            isFrontend: true,
            auth: false
        }
    }
];
