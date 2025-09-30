// Components
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/auth-layout';
import { useI18n } from '@/hooks/use-i18n';

export default function VerifyEmail({ status }: { status?: string }) {
    const { t } = useI18n();
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <AuthLayout title={t('auth:verify_email_title')} description={t('auth:verify_email_description')}>
            <Head title={t('auth:verify_email_title')} />

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {t('auth:verification_sent')}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6 text-center">
                <Button disabled={processing} variant="secondary">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                    {t('auth:resend_verification')}
                </Button>

                <TextLink href={route('logout')} method="post" className="mx-auto block text-sm">
                    {t('common:logout')}
                </TextLink>
            </form>
        </AuthLayout>
    );
}
