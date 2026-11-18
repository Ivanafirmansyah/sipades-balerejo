import { Bar, BarChart, CartesianGrid, LabelList, XAxis } from 'recharts';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';

const config = {
    jumlah: { label: 'Jiwa', color: 'var(--chart-1)' },
};

/** Kelompok umur, batang dengan label angka (shadcn "Bar Chart - Label"). */
export default function GrafikUmur({ data = [], tinggi = 280 }) {
    return (
        <ChartContainer config={config} className="aspect-auto w-full" style={{ height: tinggi }}>
            <BarChart accessibilityLayer data={data} margin={{ top: 24, left: 8, right: 8 }}>
                <CartesianGrid vertical={false} />
                <XAxis dataKey="kelompok" tickLine={false} tickMargin={10} axisLine={false} interval={0} fontSize={11} />
                <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
                <Bar dataKey="jumlah" fill="var(--color-jumlah)" radius={8}>
                    <LabelList position="top" offset={10} className="fill-foreground" fontSize={12} fontWeight={700} />
                </Bar>
            </BarChart>
        </ChartContainer>
    );
}
