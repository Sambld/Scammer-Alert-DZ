import { ScrollToTop } from '@/components/scroll-to-top';
import { Header } from '@/components/welcome/header';
import { HeroSection } from '@/components/welcome/hero-section';
// import { StatsSection } from '@/components/welcome/stats-section';
import { HowItWorksSection } from '@/components/welcome/how-it-works-section';
import { FeaturesSection } from '@/components/welcome/features-section';
import { CTASection } from '@/components/welcome/cta-section';
import { Footer } from '@/components/welcome/footer';
import { useI18n } from '@/hooks/use-i18n';
import { type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import {
    // AlertTriangle,
    // BarChart3,
    // Zap,
    CheckCircle,
    FileText,
    Heart,
    Shield,
    Users,
    UserCheck,
} from 'lucide-react';
import { useEffect, useState } from 'react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;
    const { t } = useI18n();
    const [searchQuery, setSearchQuery] = useState('');
    const [isVisible, setIsVisible] = useState(true);

    useEffect(() => {
        setIsVisible(true);
    }, []);

    // const stats = [
    //     { label: t('common:stats_reports'), value: '2,847', icon: BarChart3 },
    //     { label: t('common:stats_scammers'), value: '1,234', icon: AlertTriangle },
    //     { label: t('common:stats_users'), value: '5,623', icon: Users },
    //     { label: t('common:stats_prevented'), value: '892', icon: Shield },
    // ];

    const steps = [
        {
            title: t('common:step_1_title'),
            description: t('common:step_1_desc'),
            icon: FileText,
            color: 'from-red-500 to-orange-500',
        },
        {
            title: t('common:step_2_title'),
            description: t('common:step_2_desc'),
            icon: CheckCircle,
            color: 'from-blue-500 to-purple-500',
        },
        {
            title: t('common:step_3_title'),
            description: t('common:step_3_desc'),
            icon: Shield,
            color: 'from-green-500 to-teal-500',
        },
    ];

    const features = [
        {
            title: t('common:feature_1_title'),
            description: t('common:feature_1_desc'),
            icon: Users,
        },
        {
            title: t('common:feature_2_title'),
            description: t('common:feature_2_desc'),
            icon: UserCheck,
        },
        // {
        //     title: t('common:feature_3_title'),
        //     description: t('common:feature_3_desc'),
        //     icon: Zap,
        // },
        {
            title: t('common:feature_4_title'),
            description: t('common:feature_4_desc'),
            icon: Heart,
        },
    ];

    return (
        <>
            <Head title={t('common:welcome')}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
                <Header auth={auth} t={t} />
                <HeroSection 
                    searchQuery={searchQuery} 
                    setSearchQuery={setSearchQuery} 
                    isVisible={isVisible} 
                    t={t} 
                />
                {/* <StatsSection stats={stats} isVisible={isVisible} t={t} /> */}
                <HowItWorksSection steps={steps} isVisible={isVisible} t={t} />
                <FeaturesSection features={features} isVisible={isVisible} t={t} />
                <CTASection isVisible={isVisible} t={t} />
                <Footer t={t} />
                <ScrollToTop />
            </div>
        </>
    );
}
