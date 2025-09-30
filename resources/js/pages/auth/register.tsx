import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle, User, Mail, Lock, Eye, EyeOff } from 'lucide-react';
import { FormEventHandler, useState } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { useI18n } from '@/hooks/use-i18n';

type RegisterForm = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
};

export default function Register() {
    const { t } = useI18n();
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [formError, setFormError] = useState<string | null>(null);
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const isEmailValid = (email: string) => {
        // Simple email regex
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    };

    // Localization keys for password requirements
    const passwordRequirements = [
        {
            test: (pw: string) => pw.length >= 8,
            label: t('auth:password_requirement_length') || 'At least 8 characters',
        },
        {
            test: (pw: string) => /[A-Z]/.test(pw),
            label: t('auth:password_requirement_uppercase') || 'One uppercase letter',
        },
        {
            test: (pw: string) => /[a-z]/.test(pw),
            label: t('auth:password_requirement_lowercase') || 'One lowercase letter',
        },
        {
            test: (pw: string) => /\d/.test(pw),
            label: t('auth:password_requirement_number') || 'One number',
        },
    ];

    const isPasswordValid = () => {
        return (
            data.password.length >= 8 &&
            /[A-Z]/.test(data.password) &&
            /[a-z]/.test(data.password) &&
            /\d/.test(data.password)
        );
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        setFormError(null);

        if (!data.name.trim()) {
            setFormError(t('auth:name_required_message') || 'Name is required.');
            return;
        }

        if (!isEmailValid(data.email)) {
            setFormError(t('auth:email_invalid_message') || 'Please enter a valid email address.');
            return;
        }

        if (!isPasswordValid()) {
            setFormError(t('auth:password_invalid_message'));
            return;
        }

        if (data.password !== data.password_confirmation) {
            setFormError(t('auth:password_confirmation_invalid_message') || 'Passwords do not match.');
            return;
        }

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout title={t('auth:register_title')} description={t('auth:register_description')}>
            <Head title={t('common:register')} />
            
            {formError && (
                <div className="mb-4 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-lg px-4 py-3">
                    {formError}
                </div>
            )}

            <form className="space-y-6" onSubmit={submit}>
                {/* Name Field */}
                <div className="space-y-2">
                    <Label htmlFor="name" className="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {t('common:name')}
                    </Label>
                    <div className="relative">
                        <div className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <User className="w-5 h-5" />
                        </div>
                        <Input
                            id="name"
                            type="text"
                            required
                            autoFocus
                            tabIndex={1}
                            autoComplete="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            disabled={processing}
                            placeholder={t('auth:name_placeholder')}
                            className="pl-11 h-12 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                        />
                    </div>
                    <InputError message={errors.name} />
                </div>

                {/* Email Field */}
                <div className="space-y-2">
                    <Label htmlFor="email" className="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {t('auth:email_address')}
                    </Label>
                    <div className="relative">
                        <div className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <Mail className="w-5 h-5" />
                        </div>
                        <Input
                            id="email"
                            type="email"
                            required
                            tabIndex={2}
                            autoComplete="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            disabled={processing}
                            placeholder={t('auth:email_placeholder')}
                            className="pl-11 h-12 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                        />
                    </div>
                    <InputError message={errors.email} />
                </div>

                {/* Password Field */}
                <div className="space-y-2">
                    <Label htmlFor="password" className="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {t('common:password')}
                    </Label>
                    <div className="relative">
                        <div className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <Lock className="w-5 h-5" />
                        </div>
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            required
                            tabIndex={3}
                            autoComplete="new-password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            disabled={processing}
                            placeholder={t('auth:password_placeholder')}
                            className="pl-11 pr-11 h-12 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                        >
                            {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                        </button>
                    </div>
                    <InputError message={errors.password} />
                </div>

                {/* Confirm Password Field */}
                <div className="space-y-2">
                    <Label htmlFor="password_confirmation" className="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {t('common:confirm_password')}
                    </Label>
                    <div className="relative">
                        <div className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <Lock className="w-5 h-5" />
                        </div>
                        <Input
                            id="password_confirmation"
                            type={showConfirmPassword ? 'text' : 'password'}
                            required
                            tabIndex={4}
                            autoComplete="new-password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            disabled={processing}
                            placeholder={t('common:confirm_password')}
                            className="pl-11 pr-11 h-12 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                        />
                        <button
                            type="button"
                            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                            className="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                        >
                            {showConfirmPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                        </button>
                    </div>
                    <InputError message={errors.password_confirmation} />
                </div>

                {/* Password Requirements */}
                <div className="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4">
                    <h4 className="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        {t('auth:password_requirements_title') || 'Password Requirements:'}
                    </h4>
                    <ul className="text-xs text-slate-600 dark:text-slate-400 space-y-1">
                        {passwordRequirements.map((req, idx) => (
                            <li
                                key={idx}
                                className={`flex items-center ${req.test(data.password) ? 'text-green-600 dark:text-green-400' : ''}`}
                            >
                                <div
                                    className={`w-1.5 h-1.5 rounded-full mr-2 ${req.test(data.password) ? 'bg-green-500' : 'bg-slate-300 dark:bg-slate-600'}`}
                                ></div>
                                {req.label}
                            </li>
                        ))}
                    </ul>
                </div>

                {/* Submit Button */}
                <Button 
                    type="submit" 
                    className="w-full h-12 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-[1.02]" 
                    tabIndex={5} 
                    disabled={processing}
                >
                    {processing ? (
                        <>
                            <LoaderCircle className="h-5 w-5 animate-spin mr-2" />
                            {t('auth:creating_account') || 'Creating account...'}
                        </>
                    ) : (
                        t('auth:sign_up')
                    )}
                </Button>

                {/* Divider */}
                <div className="relative my-6">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div className="relative flex justify-center text-sm">
                        <span className="px-4 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                            {t('common:or') || 'or'}
                        </span>
                    </div>
                </div>

                {/* Login Link */}
                <div className="text-center">
                    <p className="text-sm text-slate-600 dark:text-slate-400">
                        {t('auth:already_have_account')}{' '}
                        <TextLink 
                            href={route('login')} 
                            tabIndex={6}
                            className="font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                        >
                            {t('auth:log_in')}
                        </TextLink>
                    </p>
                </div>
            </form>
        </AuthLayout>
    );
}
