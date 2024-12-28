<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        fill="none"
        class="h-9 w-auto"
    >
        <!-- Background Circle -->
        <circle cx="50" cy="50" r="45" fill="#4F46E5" />

        <!-- Brush Stroke -->
        <path
            d="M30 60C30 60 35 40 50 40C65 40 70 60 70 60"
            stroke="#FFFFFF"
            stroke-width="6"
            stroke-linecap="round"
        />
        <path
            d="M50 40L50 20"
            stroke="#FFFFFF"
            stroke-width="6"
            stroke-linecap="round"
        />

        <!-- Palette -->
        <circle cx="50" cy="70" r="10" fill="#FBBF24" />
        <circle cx="40" cy="80" r="6" fill="#34D399" />
        <circle cx="60" cy="80" r="6" fill="#EF4444" />

        <!-- Frame -->
        <rect
            x="20"
            y="20"
            width="60"
            height="60"
            stroke="#FFFFFF"
            stroke-width="4"
            fill="none"
        />
    </svg>
</template>
