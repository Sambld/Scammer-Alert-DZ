import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { useI18n } from '@/hooks/use-i18n';
import { LanguageSwitcher } from '@/components/LanguageSwitcher';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { HeroBackground } from '@/components/hero-background';
import { AnimatedCounter } from '@/components/animations';
import { MobileMenu } from '@/components/mobile-menu';
import { ScrollToTop } from '@/components/scroll-to-top';
import { ThemeToggle } from '@/components/theme-toggle';
import { 
    Search, 
    FileText, 
    Phone, 
    Eye, 
    Shield, 
    BarChart3, 
    Users, 
    AlertTriangle,
    CheckCircle,
    Zap,
    Heart,
    UserCheck,
    Rocket,
    TrendingUp,
    Clock,
    Target,
    Star
} from 'lucide-react';
import { useState, useEffect } from 'react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;
    const { t } = useI18n();
    const [searchQuery, setSearchQuery] = useState('');
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        setIsVisible(true);
    }, []);

    const stats = [
        { label: t('common:stats_reports'), value: '2,847', icon: BarChart3 },
        { label: t('common:stats_scammers'), value: '1,234', icon: AlertTriangle },
        { label: t('common:stats_users'), value: '5,623', icon: Users },
        { label: t('common:stats_prevented'), value: '892', icon: Shield }
    ];

    const steps = [
        {
            title: t('common:step_1_title'),
            description: t('common:step_1_desc'),
            icon: FileText,
            color: 'from-red-500 to-orange-500'
        },
        {
            title: t('common:step_2_title'),
            description: t('common:step_2_desc'),
            icon: CheckCircle,
            color: 'from-blue-500 to-purple-500'
        },
        {
            title: t('common:step_3_title'),
            description: t('common:step_3_desc'),
            icon: Shield,
            color: 'from-green-500 to-teal-500'
        }
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
        {
            title: t('common:feature_3_title'),
            description: t('common:feature_3_desc'),
            icon: Zap,
        },
        {
            title: t('common:feature_4_title'),
            description: t('common:feature_4_desc'),
            icon: Heart,
        }
    ];

    return (
        <>
            <Head title={t('common:welcome')}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
                {/* Header */}
                <header className="relative z-50 w-full border-b border-slate-200/50 bg-white/80 backdrop-blur-md dark:border-slate-700/50 dark:bg-slate-900/80">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <nav className="flex h-16 items-center justify-between">
                            <div className="flex items-center space-x-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold text-lg">
                                    E
                                </div>
                                <span className="text-xl font-bold text-slate-900 dark:text-white">
                                    EscrocAlert
                                </span>
                            </div>
                            
                            {/* Desktop Navigation */}
                            <div className="hidden lg:flex items-center gap-4">
                                <ThemeToggle />
                                <LanguageSwitcher />
                                {auth.user ? (
                                    <Button asChild variant="default">
                                        <Link href={route('dashboard')}>
                                            {t('common:dashboard')}
                                        </Link>
                                    </Button>
                                ) : (
                                    <div className="flex items-center gap-2">
                                        <Button asChild variant="ghost">
                                            <Link href={route('login')}>
                                                {t('common:login')}
                                            </Link>
                                        </Button>
                                        <Button asChild variant="default">
                                            <Link href={route('register')}>
                                                {t('common:register')}
                                            </Link>
                                        </Button>
                                    </div>
                                )}
                            </div>

                            {/* Mobile Navigation */}
                            <MobileMenu auth={auth} />
                        </nav>
                    </div>
                </header>

                {/* Hero Section */}
                <section className="relative overflow-hidden pt-20 pb-32">
                    <HeroBackground />
                    <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className={`text-center transform transition-all duration-1000 ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}>
                            <div className="inline-flex items-center px-4 py-2 rounded-full bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 mb-8">
                                <Shield className="w-4 h-4 text-red-600 dark:text-red-400 mr-2" />
                                <span className="text-red-600 dark:text-red-400 text-sm font-medium">{t('common:protection_for_algeria')}</span>
                            </div>
                            
                            <h1 className="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                                <span className="bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 bg-clip-text text-transparent">
                                    {t('common:hero_title')}
                                </span>
                            </h1>
                            
                            <p className="mx-auto mt-8 max-w-3xl text-xl leading-relaxed text-slate-600 dark:text-slate-300">
                                {t('common:hero_subtitle')}
                            </p>

                            {/* Enhanced Search Bar */}
                            <div className={`mx-auto mt-12 max-w-3xl transform transition-all duration-1000 delay-300 ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}>
                                <div className="relative">
                                    <div className="absolute inset-0 bg-gradient-to-r from-red-500/20 to-orange-500/20 rounded-2xl blur-xl"></div>
                                    <div className="relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-2 shadow-xl">
                                        <div className="flex flex-col sm:flex-row gap-3">
                                            <div className="flex-1 relative">
                                                <div className="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                                                    <Search className="w-5 h-5" />
                                                </div>
                                                <Input
                                                    type="text"
                                                    placeholder={t('common:search_placeholder')}
                                                    value={searchQuery}
                                                    onChange={(e) => setSearchQuery(e.target.value)}
                                                    className="pl-12 h-14 text-lg border-0 focus-visible:ring-0 bg-transparent"
                                                />
                                            </div>
                                            <Button size="lg" className="h-14 px-8 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 rounded-xl font-semibold">
                                                {t('common:quick_search')}
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Enhanced Action Cards */}
                            <div className={`mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto transform transition-all duration-1000 delay-500 ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}>
                                <Card className="group hover:scale-105 transition-all duration-300 bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-950/20 dark:to-orange-950/20 border-red-200 dark:border-red-800 cursor-pointer">
                                    <div className="p-6 text-center">
                                        <div className="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                                            <FileText className="w-8 h-8 text-white" />
                                        </div>
                                        <h3 className="font-bold text-lg text-slate-900 dark:text-white mb-2">{t('common:report_scammer')}</h3>
                                        <p className="text-slate-600 dark:text-slate-400 text-sm">{t('common:submit_details_suspicious')}</p>
                                    </div>
                                </Card>
                                
                                <Card className="group hover:scale-105 transition-all duration-300 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-950/20 dark:to-purple-950/20 border-blue-200 dark:border-blue-800 cursor-pointer">
                                    <div className="p-6 text-center">
                                        <div className="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                                            <Phone className="w-8 h-8 text-white" />
                                        </div>
                                        <h3 className="font-bold text-lg text-slate-900 dark:text-white mb-2">{t('common:check_number')}</h3>
                                        <p className="text-slate-600 dark:text-slate-400 text-sm">{t('common:verify_phone_instantly')}</p>
                                    </div>
                                </Card>
                                
                                <Card className="group hover:scale-105 transition-all duration-300 bg-gradient-to-br from-green-50 to-teal-50 dark:from-green-950/20 dark:to-teal-950/20 border-green-200 dark:border-green-800 cursor-pointer">
                                    <div className="p-6 text-center">
                                        <div className="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                                            <Eye className="w-8 h-8 text-white" />
                                        </div>
                                        <h3 className="font-bold text-lg text-slate-900 dark:text-white mb-2">{t('common:verify_profile')}</h3>
                                        <p className="text-slate-600 dark:text-slate-400 text-sm">{t('common:check_social_profiles')}</p>
                                    </div>
                                </Card>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Stats Section - Enhanced Platform Impact */}
                <section className="relative py-24 bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-blue-950/20">
                    <div className="absolute inset-0">
                        {/* Animated background elements */}
                        <div className="absolute top-0 left-0 w-full h-full">
                            {[...Array(3)].map((_, i) => (
                                <div
                                    key={i}
                                    className="absolute w-64 h-64 bg-gradient-to-r from-blue-200/20 to-purple-200/20 rounded-full blur-3xl animate-pulse"
                                    style={{
                                        left: `${20 + i * 30}%`,
                                        top: `${10 + i * 20}%`,
                                        animationDelay: `${i * 2}s`,
                                        animationDuration: `${4 + i}s`
                                    }}
                                />
                            ))}
                        </div>
                    </div>
                    
                    <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <div className="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 mb-6">
                                <TrendingUp className="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" />
                                <span className="text-blue-600 dark:text-blue-400 text-sm font-medium">{t('common:platform_impact')}</span>
                            </div>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                                {t('common:real_numbers_protection')}
                            </h2>
                            <p className="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                                {t('common:community_difference_desc')}
                            </p>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                            {stats.map((stat, index) => (
                                <Card 
                                    key={index}
                                    className={`relative overflow-hidden group hover:scale-105 transition-all duration-700 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border-0 shadow-lg hover:shadow-2xl ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}
                                    style={{ transitionDelay: `${800 + index * 100}ms` }}
                                >
                                    {/* Gradient overlay */}
                                    <div className="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    {/* Icon background effect */}
                                    <div className="absolute top-4 right-4 w-20 h-20 bg-gradient-to-br from-blue-100/50 to-purple-100/50 dark:from-blue-900/30 dark:to-purple-900/30 rounded-full blur-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    <div className="relative p-6 lg:p-8 text-center">
                                        {/* Mobile-optimized icon */}
                                        <div className="inline-flex items-center justify-center w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/50 dark:to-purple-900/50 rounded-2xl mb-4 group-hover:scale-110 transition-transform">
                                            <stat.icon className="w-8 h-8 lg:w-10 lg:h-10 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        
                                        {/* Enhanced number display */}
                                        <div className="text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">
                                            <AnimatedCounter value={stat.value} />
                                        </div>
                                        
                                        {/* Better label typography */}
                                        <div className="text-sm lg:text-base text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                                            {stat.label}
                                        </div>
                                        
                                        {/* Enhanced progress indicator for mobile */}
                                        <div className="mt-4 sm:hidden">
                                            <div className="h-1 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                                <div 
                                                    className="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full transition-all duration-1000"
                                                    style={{ 
                                                        width: `${Math.min(100, (parseInt(stat.value.replace(/[,+]/g, '')) / (index === 0 ? 50000 : index === 1 ? 10000 : index === 2 ? 5000 : 1000)) * 100)}%`,
                                                        transitionDelay: `${1000 + index * 200}ms`
                                                    }}
                                                ></div>
                                            </div>
                                            <div className="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                {index === 0 ? t('common:growing_daily') : index === 1 ? t('common:reports_verified') : index === 2 ? t('common:active_users') : t('common:response_time')}
                                            </div>
                                        </div>

                                        {/* Desktop hover indicator */}
                                        <div className="hidden sm:block absolute bottom-3 right-3 w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity animate-pulse"></div>
                                    </div>
                                </Card>
                            ))}
                        </div>
                       

                        {/* Enhanced additional info for mobile */}
                        <div className="mt-12 text-center">
                            <div className="sm:hidden mb-8">
                                <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                    {t('common:updated_realtime_community')}
                                </p>
                                <div className="flex items-center justify-center space-x-6 text-xs text-slate-400 dark:text-slate-500">
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                        {t('common:live_data')}
                                    </div>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        {t('common:verified')}
                                    </div>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        {t('common:trusted')}
                                    </div>
                                </div>
                            </div>
                            <div className="hidden sm:block">
                                <p className="text-sm text-slate-500 dark:text-slate-400">
                                    {t('common:realtime_stats_powered')}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* How It Works Section - Enhanced Mobile Timeline */}
                <section className="py-24 bg-white dark:bg-slate-800 relative overflow-hidden">
                    <div className="absolute inset-0">
                        <div className="absolute inset-0 bg-gradient-to-br from-orange-50/50 via-transparent to-red-50/50 dark:from-orange-950/10 dark:via-transparent dark:to-red-950/10"></div>
                        {/* Mobile background pattern */}
                        <div className="md:hidden absolute inset-0 opacity-5">
                            <div className="absolute inset-0" style={{
                                backgroundImage: `url("data:image/svg+xml,%3csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='grid' width='40' height='40' patternUnits='userSpaceOnUse'%3e%3cpath d='m 40 0 l 0 40 l -40 0 l 0 -40 z' fill='none' stroke='%23000000' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23grid)' /%3e%3c/svg%3e")`,
                            }}></div>
                        </div>
                    </div>
                    
                    <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-20">
                            <div className="inline-flex items-center px-4 py-2 rounded-full bg-orange-50 dark:bg-orange-950/30 border border-orange-200 dark:border-orange-800 mb-6">
                                <Target className="w-4 h-4 text-orange-600 dark:text-orange-400 mr-2" />
                                <span className="text-orange-600 dark:text-orange-400 text-sm font-medium">{t('common:how_it_works_badge')}</span>
                            </div>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-6">
                                {t('common:how_it_works')}
                            </h2>
                            <p className="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                                {t('common:three_simple_steps')}
                            </p>
                        </div>
                        
                        <div className="relative">
                            {/* Desktop Timeline line */}
                            <div className="hidden md:block absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-red-200 via-orange-200 to-yellow-200 dark:from-red-800 dark:via-orange-800 dark:to-yellow-800"></div>
                            
                            {/* Mobile Timeline line */}
                            <div className="md:hidden absolute left-8 top-0 w-0.5 h-full bg-gradient-to-b from-red-200 via-orange-200 to-yellow-200 dark:from-red-800 dark:via-orange-800 dark:to-yellow-800"></div>
                            
                            <div className="space-y-12 md:space-y-16 relative">
                                {steps.map((step, index) => (
                                    <div 
                                        key={index}
                                        className={`relative flex flex-col md:flex-row items-start md:items-center ${index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse'} transform transition-all duration-700 ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}
                                        style={{ transitionDelay: `${1200 + index * 200}ms` }}
                                    >
                                        {/* Mobile Layout */}
                                        <div className="md:hidden flex items-start space-x-4 w-full">
                                            {/* Mobile Timeline Icon */}
                                            <div className="flex-shrink-0">
                                                <div className="relative">
                                                    <div className={`w-16 h-16 rounded-2xl bg-gradient-to-r ${step.color} flex items-center justify-center text-white shadow-lg ring-4 ring-white dark:ring-slate-800`}>
                                                        <step.icon className="w-8 h-8" />
                                                    </div>
                                                    {/* Step number */}
                                                    <div className="absolute -top-2 -right-2 w-6 h-6 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-600 rounded-full flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                                        {index + 1}
                                                    </div>
                                                    {/* Connection line for mobile */}
                                                    {index < steps.length - 1 && (
                                                        <div className="absolute top-16 left-8 w-0.5 h-12 bg-gradient-to-b from-orange-300 to-red-300 dark:from-orange-700 dark:to-red-700 transform -translate-x-1/2"></div>
                                                    )}
                                                </div>
                                            </div>
                                            
                                            {/* Mobile Content */}
                                            <div className="flex-1 pb-8">
                                                <Card className="p-6 bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-l-orange-500">
                                                    <div className="flex items-center justify-between mb-3">
                                                        <h3 className="text-xl font-bold text-slate-900 dark:text-white">
                                                            {step.title}
                                                        </h3>
                                                        {/* Mobile step indicator */}
                                                        <div className="flex items-center space-x-1">
                                                            {[...Array(steps.length)].map((_, i) => (
                                                                <div 
                                                                    key={i} 
                                                                    className={`w-2 h-2 rounded-full transition-colors ${
                                                                        i <= index ? 'bg-orange-500' : 'bg-slate-300 dark:bg-slate-600'
                                                                    }`}
                                                                ></div>
                                                            ))}
                                                        </div>
                                                    </div>
                                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                                                        {step.description}
                                                    </p>
                                                    {/* Mobile progress indicator */}
                                                    <div className="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                                                        <div className="flex items-center">
                                                            <Clock className="w-4 h-4 mr-2" />
                                                            {t('common:step_indicator', { current: index + 1, total: steps.length })}
                                                        </div>
                                                        <div className="text-xs">
                                                            {t('common:progress_complete', { percent: Math.round(((index + 1) / steps.length) * 100) })}
                                                        </div>
                                                    </div>
                                                </Card>
                                            </div>
                                        </div>

                                        {/* Desktop Layout */}
                                        <div className="hidden md:block w-full">
                                            <div className="relative flex items-center min-h-[200px]">
                                                {/* Left Content (for even index items) */}
                                                <div className={`w-5/12 ${index % 2 === 0 ? 'pr-8' : ''}`}>
                                                    {index % 2 === 0 && (
                                                        <Card className="p-8 bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 shadow-xl hover:shadow-2xl transition-all duration-300 border-l-4 border-l-orange-500">
                                                            <div className="flex items-start space-x-4">
                                                                <div className="flex-shrink-0">
                                                                    <div className={`w-12 h-12 rounded-xl bg-gradient-to-r ${step.color} flex items-center justify-center text-white font-bold text-xl`}>
                                                                        {index + 1}
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <h3 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">
                                                                        {step.title}
                                                                    </h3>
                                                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed">
                                                                        {step.description}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </Card>
                                                    )}
                                                </div>
                                                
                                                {/* Center Icon - Always centered */}
                                                <div className="w-2/12 flex justify-center relative z-10">
                                                    <div className="w-20 h-20 rounded-full bg-white dark:bg-slate-800 border-4 border-slate-200 dark:border-slate-600 flex items-center justify-center shadow-lg">
                                                        <div className={`w-12 h-12 rounded-full bg-gradient-to-r ${step.color} flex items-center justify-center text-white`}>
                                                            <step.icon className="w-6 h-6" />
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {/* Right Content (for odd index items) */}
                                                <div className={`w-5/12 ${index % 2 === 1 ? 'pl-8' : ''}`}>
                                                    {index % 2 === 1 && (
                                                        <Card className="p-8 bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 shadow-xl hover:shadow-2xl transition-all duration-300 border-r-4 border-r-orange-500">
                                                            <div className="flex items-start space-x-4">
                                                                <div className="flex-shrink-0">
                                                                    <div className={`w-12 h-12 rounded-xl bg-gradient-to-r ${step.color} flex items-center justify-center text-white font-bold text-xl`}>
                                                                        {index + 1}
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <h3 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">
                                                                        {step.title}
                                                                    </h3>
                                                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed">
                                                                        {step.description}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </Card>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        
                        {/* Mobile call-to-action and progress summary */}
                        <div className="mt-16 text-center">
                            <div className="md:hidden">
                                {/* Mobile progress summary */}
                                <div className="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-lg border border-orange-200 dark:border-orange-800">
                                    <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-4">
                                        {t('common:ready_to_start')}
                                    </h3>
                                    <div className="flex items-center justify-center space-x-4 mb-4">
                                        {steps.map((step, index) => (
                                            <div key={index} className="flex flex-col items-center">
                                                <div className={`w-8 h-8 rounded-full bg-gradient-to-r ${step.color} flex items-center justify-center text-white text-sm font-bold mb-1`}>
                                                    {index + 1}
                                                </div>
                                                <div className="text-xs text-slate-600 dark:text-slate-400 text-center max-w-16">
                                                    {step.title.split(' ')[0]}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                    <Button size="lg" className="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white">
                                        <Rocket className="w-5 h-5 mr-2" />
                                        {t('common:get_started_now')}
                                    </Button>
                                </div>
                            </div>
                            
                            {/* Desktop CTA */}
                            <div className="hidden md:block">
                                <p className="text-slate-600 dark:text-slate-400 mb-6">
                                    {t('common:join_thousands_users')}
                                </p>
                                <Button size="lg" className="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-8">
                                    <Shield className="w-5 h-5 mr-2" />
                                    {t('common:start_protecting_now')}
                                </Button>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features Section - Enhanced Bento Grid Layout */}
                <section className="py-24 bg-gradient-to-br from-slate-50 via-white to-orange-50 dark:from-slate-900 dark:via-slate-800 dark:to-orange-950/20 relative overflow-hidden">
                    {/* Background decorations */}
                    <div className="absolute inset-0">
                        <div className="absolute top-20 left-10 w-32 h-32 bg-orange-200/20 dark:bg-orange-800/20 rounded-full blur-3xl"></div>
                        <div className="absolute bottom-20 right-10 w-40 h-40 bg-red-200/20 dark:bg-red-800/20 rounded-full blur-3xl"></div>
                    </div>
                    
                    <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16 lg:mb-20">
                            <div className="inline-flex items-center px-4 py-2 rounded-full bg-orange-50 dark:bg-orange-950/30 border border-orange-200 dark:border-orange-800 mb-6">
                                <Star className="w-4 h-4 text-orange-600 dark:text-orange-400 mr-2" />
                                <span className="text-orange-600 dark:text-orange-400 text-sm font-medium">{t('common:why_choose_us_badge')}</span>
                            </div>
                            <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                                {t('common:why_choose_us')}
                            </h2>
                            <p className="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                                {t('common:built_by_community')}
                            </p>
                        </div>
                        
                        {/* Enhanced Grid Layout */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-6">
                            {features.map((feature, index) => {
                                // Define custom grid layouts for different screen sizes
                                let gridClasses = '';
                                if (index === 0) {
                                    // First feature - Hero feature
                                    gridClasses = 'sm:col-span-2 lg:col-span-6 lg:row-span-2';
                                } else if (index === 1) {
                                    // Second feature
                                    gridClasses = 'sm:col-span-2 lg:col-span-6';
                                } else if (index === 2) {
                                    // Third feature
                                    gridClasses = 'sm:col-span-1 lg:col-span-4';
                                } else if (index === 3) {
                                    // Fourth feature
                                    gridClasses = 'sm:col-span-1 lg:col-span-4';
                                } else {
                                    // Default for additional features
                                    gridClasses = 'sm:col-span-1 lg:col-span-4';
                                }

                                return (
                                    <Card 
                                        key={index}
                                        className={`group relative overflow-hidden bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm hover:shadow-2xl transition-all duration-700 hover:scale-[1.02] border-0 shadow-lg ${gridClasses} ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}
                                        style={{ transitionDelay: `${1800 + index * 150}ms` }}
                                    >
                                        {/* Gradient overlay */}
                                        <div className="absolute inset-0 bg-gradient-to-br from-red-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        
                                        {/* Background pattern for hero feature */}
                                        {index === 0 && (
                                            <div className="absolute inset-0 opacity-5">
                                                <div className="absolute inset-0" style={{
                                                    backgroundImage: `url("data:image/svg+xml,%3csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='hero-grid' width='60' height='60' patternUnits='userSpaceOnUse'%3e%3cpath d='m 60 0 l 0 60 l -60 0 l 0 -60 z' fill='none' stroke='%23f97316' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23hero-grid)' /%3e%3c/svg%3e")`,
                                                }}></div>
                                            </div>
                                        )}
                                        
                                        <div className={`relative ${index === 0 ? 'p-8 lg:p-12' : 'p-6 lg:p-8'}`}>
                                            {/* Icon and title section */}
                                            <div className={`flex ${index === 0 ? 'flex-col items-center text-center lg:items-start lg:text-left' : 'items-center'} ${index === 0 ? 'mb-8' : 'mb-6'} space-x-0 ${index === 0 ? 'lg:space-x-0' : 'space-x-4'}`}>
                                                <div className={`${index === 0 ? 'w-20 h-20 lg:w-24 lg:h-24 mb-4 lg:mb-6' : 'w-12 h-12 lg:w-14 lg:h-14'} bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 flex-shrink-0`}>
                                                    <feature.icon className={`${index === 0 ? 'w-10 h-10 lg:w-12 lg:h-12' : 'w-6 h-6 lg:w-7 lg:h-7'} text-red-600 dark:text-red-400`} />
                                                </div>
                                                <div className={index === 0 ? '' : 'flex-1'}>
                                                    <h3 className={`font-bold text-slate-900 dark:text-white ${index === 0 ? 'text-2xl lg:text-3xl mb-4' : 'text-lg lg:text-xl'}`}>
                                                        {feature.title}
                                                    </h3>
                                                    {index === 0 && (
                                                        <div className="flex items-center justify-center lg:justify-start space-x-1 mb-4">
                                                            {[...Array(5)].map((_, i) => (
                                                                <Star key={i} className="w-5 h-5 text-yellow-400 fill-current" />
                                                            ))}
                                                            <span className="ml-2 text-sm text-slate-600 dark:text-slate-400">{t('common:trusted_by_thousands')}</span>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                            
                                            {/* Description */}
                                            <p className={`text-slate-600 dark:text-slate-400 leading-relaxed ${index === 0 ? 'text-base lg:text-lg text-center lg:text-left' : 'text-sm lg:text-base'}`}>
                                                {feature.description}
                                            </p>
                                            
                                            {/* Additional content for hero feature */}
                                            {index === 0 && (
                                                <div className="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                                                    <div className="flex flex-wrap gap-2 justify-center lg:justify-start">
                                                        <span className="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-medium rounded-full">
                                                            {t('common:community_driven_badge')}
                                                        </span>
                                                        <span className="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-medium rounded-full">
                                                            {t('common:free_to_use_badge')}
                                                        </span>
                                                        <span className="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs font-medium rounded-full">
                                                            {t('common:realtime_updates_badge')}
                                                        </span>
                                                    </div>
                                                </div>
                                            )}
                                            
                                            {/* Hover indicator */}
                                            <div className="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                            </div>
                                        </div>
                                    </Card>
                                );
                            })}
                        </div>
                        
                        {/* Bottom call-to-action for mobile */}
                        <div className="mt-16 text-center lg:hidden">
                            <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                {t('common:join_thousands_protecting')}
                            </p>
                            <Button size="lg" className="bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white px-8">
                                <Shield className="w-5 h-5 mr-2" />
                                Start Protecting Now
                            </Button>
                        </div>
                    </div>
                </section>

                {/* CTA Section - Modern Design */}
                <section className="relative py-24 overflow-hidden">
                    {/* Animated Background */}
                    <div className="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500"></div>
                    <div className="absolute inset-0 bg-gradient-to-br from-black/20 via-transparent to-black/20"></div>
                    
                    {/* Floating Elements */}
                    <div className="absolute inset-0">
                        {[...Array(5)].map((_, i) => (
                            <div
                                key={i}
                                className="absolute w-32 h-32 bg-white/10 rounded-full blur-xl animate-float"
                                style={{
                                    left: `${15 + i * 20}%`,
                                    top: `${20 + (i % 2) * 40}%`,
                                    animationDelay: `${i * 0.8}s`,
                                    animationDuration: `${4 + i * 0.5}s`
                                }}
                            />
                        ))}
                    </div>

                    <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className={`text-center transform transition-all duration-1000 ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`} style={{ transitionDelay: '2200ms' }}>
                            {/* Badge */}
                            <div className="inline-flex items-center px-6 py-3 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 mb-8">
                                <Rocket className="w-5 h-5 text-white mr-2" />
                                <span className="text-white font-medium">{t('common:join_the_movement')}</span>
                            </div>
                            
                            <h2 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8 leading-tight">
                                {t('common:cta_title')}
                            </h2>
                            
                            <p className="mx-auto max-w-3xl text-xl leading-relaxed text-white/90 mb-12">
                                {t('common:cta_subtitle')}
                            </p>
                            
                            {/* Action Buttons */}
                            <div className="flex flex-col sm:flex-row gap-6 justify-center items-center">
                                <Button 
                                    size="lg" 
                                    className="bg-white text-slate-900 hover:bg-slate-100 font-semibold px-12 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
                                >
                                    <span className="text-lg">{t('common:join_community')}</span>
                                </Button>
                                
                                <Button 
                                    size="lg" 
                                    variant="outline" 
                                    className="border-2 border-white/30 text-white hover:bg-white/10 backdrop-blur-sm font-semibold px-12 py-4 rounded-2xl transition-all duration-300 hover:scale-105"
                                >
                                    <span className="text-lg">{t('common:learn_more')}</span>
                                </Button>
                            </div>
                            
                            {/* Trust Indicators */}
                            <div className="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-white mb-2">{t('common:community_protection_24_7')}</div>
                                    <div className="text-white/80">{t('common:community_protection')}</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-white mb-2">{t('common:free_platform_100')}</div>
                                    <div className="text-white/80">{t('common:free_platform')}</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-white mb-2">{t('common:protected_users_5000')}</div>
                                    <div className="text-white/80">{t('common:protected_users')}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-slate-900 py-12">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="flex items-center justify-center space-x-3 mb-4">
                                <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold">
                                    E
                                </div>
                                <span className="text-lg font-bold text-white">
                                    EscrocAlert
                                </span>
                            </div>
                            <p className="text-slate-400">
                                {t('common:footer_tagline')}
                            </p>
                        </div>
                    </div>
                </footer>

                {/* Scroll to Top Button */}
                <ScrollToTop />
            </div>
        </>
    );
}
