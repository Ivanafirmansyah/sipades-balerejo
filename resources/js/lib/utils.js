import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

/** Gabungkan class Tailwind (utilitas bawaan shadcn/ui). */
export function cn(...inputs) {
    return twMerge(clsx(inputs));
}
