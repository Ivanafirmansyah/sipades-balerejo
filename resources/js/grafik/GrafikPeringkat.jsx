import { Bar, BarChart, CartesianGrid, LabelList, XAxis, YAxis } from 'recharts';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';

/**
 * Batang horizontal berperingkat, mis. pekerjaan / agama (shadcn "Bar Chart - Horizontal" + label).
 * data: [{ nama, jumlah }]
 */
export default function GrafikPeringkat({ data = [], label = 'Jiwa', warna = 'var(--chart-3)', tinggi }) {
    const config = { jumlah: { label, color: warna } };
    const h = tinggi || Math.max(160, data.length * 44 + 20);

    return (
        <ChartContainer config={config} className="aspect-auto w-full" style={{ height: h }}>
            <BarChart accessibilityLayer data={data} layout="vertical" margin={{ left: 4, right: 36 }}>
                <CartesianGrid horizontal={false} />
                <YAxis dataKey="nama" type="category" tickLine={false} tickMargin={8} axisLine={false} width={118} fontSize={11} />
                <XAxis dataKey="jumlah" type="number" hide allowDecimals={false} />
                <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
                <Bar dataKey="jumlah" layout="vertical" fill="var(--color-jumlah)" radius={6}>
                    <LabelList dataKey="jumlah" position="right" offset={8} className="fill-foreground" fontSize={12} fontWeight={700} />
                </Bar>
            </BarChart>
        </ChartContainer>
    );
}
