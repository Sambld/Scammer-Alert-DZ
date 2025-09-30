import { useEffect, useState } from 'react';

export function HeroBackground() {
    const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });

    useEffect(() => {
        const handleMouseMove = (e: MouseEvent) => {
            setMousePosition({ x: e.clientX, y: e.clientY });
        };

        window.addEventListener('mousemove', handleMouseMove);
        return () => window.removeEventListener('mousemove', handleMouseMove);
    }, []);

    return (
        <div className="absolute inset-0 overflow-hidden">
            {/* Animated gradient background */}
            <div className="absolute inset-0 bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 dark:from-red-950/20 dark:via-orange-950/20 dark:to-yellow-950/20"></div>
            
            {/* Floating geometric shapes */}
            <div className="absolute inset-0">
                {[...Array(6)].map((_, i) => (
                    <div
                        key={i}
                        className={`absolute w-20 h-20 bg-gradient-to-r from-red-200/30 to-orange-200/30 rounded-full animate-float`}
                        style={{
                            left: `${10 + i * 15}%`,
                            top: `${20 + (i % 3) * 20}%`,
                            animationDelay: `${i * 0.5}s`,
                            animationDuration: `${3 + i * 0.5}s`
                        }}
                    />
                ))}
            </div>

            {/* Interactive mouse-following gradient */}
            <div
                className="absolute w-96 h-96 bg-gradient-to-r from-red-300/20 to-orange-300/20 rounded-full blur-3xl transition-all duration-1000 ease-out"
                style={{
                    left: mousePosition.x - 192,
                    top: mousePosition.y - 192,
                }}
            />

            {/* Grid pattern overlay */}
            <div 
                className="absolute inset-0 opacity-10"
                style={{
                    backgroundImage: `url("data:image/svg+xml,%3csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='grid' width='60' height='60' patternUnits='userSpaceOnUse'%3e%3cpath d='m 60 0 l 0 60 l -60 0 l 0 -60 z' fill='none' stroke='%23000000' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23grid)' /%3e%3c/svg%3e")`,
                }}
            />
        </div>
    );
}

export function StatsAnimatedBg() {
    return (
        <div className="absolute inset-0 overflow-hidden">
            {/* Animated lines */}
            {[...Array(4)].map((_, i) => (
                <div
                    key={i}
                    className="absolute h-px bg-gradient-to-r from-transparent via-red-300 to-transparent animate-pulse"
                    style={{
                        width: '100%',
                        top: `${25 * i}%`,
                        animationDelay: `${i * 0.3}s`,
                        animationDuration: '2s'
                    }}
                />
            ))}
            
            {/* Floating particles */}
            {[...Array(8)].map((_, i) => (
                <div
                    key={i}
                    className="absolute w-2 h-2 bg-red-400 rounded-full animate-ping"
                    style={{
                        left: `${Math.random() * 100}%`,
                        top: `${Math.random() * 100}%`,
                        animationDelay: `${Math.random() * 2}s`,
                        animationDuration: `${2 + Math.random() * 2}s`
                    }}
                />
            ))}
        </div>
    );
}
