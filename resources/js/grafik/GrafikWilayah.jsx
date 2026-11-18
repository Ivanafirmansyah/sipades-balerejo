import { Bar, BarChart, CartesianGrid, XAxis, YAxis } from 'recharts';
import { ChartContainer, ChartLegend, ChartLegendContent, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';

const config = {
    laki: { label: 'Laki-laki', color: 'var(--chart-1)' },
    perempuan: { label: 'Perempuan', color: 'var(--chart-2)' },
};

/** Jumlah warga per RT/RW, batang bertumpuk laki-laki & perempuan (shadcn "Bar Chart - Stacked + Legend"). */
export default function GrafikWilayah({ data = [], tinggi = 300 }) {
    return (
        <ChartContainer config={config} className="aspect-auto w-full" style={{ height: tinggi }}>
            <BarChart accessibilityLayer data={data} margin={{ top: 8, left: -18, right: 8 }}>
                <CartesianGrid vertical={false} />
                <XAxis dataKey="wilayah" tickLine={false} tickMargin={10} axisLine={false} interval={0} fontSize={11} />
                <YAxis allowDecimals={false} tickLine={false} axisLine={false} width={40} fontSize={11} />
                <ChartTooltip cursor={false} content={<ChartTooltipContent />} />
                <ChartLegend content={<ChartLegendContent />} />
                <Bar dataKey="laki" stackId="a" fill="var(--color-laki)" radius={[0, 0, 4, 4]} />
                <Bar dataKey="perempuan" stackId="a" fill="var(--color-perempuan)" radius={[4, 4, 0, 0]} />
            </BarChart>
        </ChartContainer>
    );
}
