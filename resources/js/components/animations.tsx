import { cn } from '@/lib/utils';

interface LoadingSpinnerProps {
    size?: 'sm' | 'md' | 'lg';
    className?: string;
}

export function LoadingSpinner({ size = 'md', className }: LoadingSpinnerProps) {
    const sizeClasses = {
        sm: 'w-4 h-4',
        md: 'w-8 h-8',
        lg: 'w-12 h-12'
    };

    return (
        <div
            className={cn(
                'animate-spin rounded-full border-2 border-gray-300 border-t-blue-600',
                sizeClasses[size],
                className
            )}
        />
    );
}

interface PulsingDotProps {
    className?: string;
}

export function PulsingDot({ className }: PulsingDotProps) {
    return (
        <div className={cn('relative', className)}>
            <div className="w-3 h-3 bg-red-500 rounded-full animate-ping absolute"></div>
            <div className="w-3 h-3 bg-red-600 rounded-full"></div>
        </div>
    );
}

interface AnimatedCounterProps {
    value: string;
    className?: string;
}

export function AnimatedCounter({ value, className }: AnimatedCounterProps) {
    return (
        <span className={cn('tabular-nums transition-all duration-500', className)}>
            {value}
        </span>
    );
}
