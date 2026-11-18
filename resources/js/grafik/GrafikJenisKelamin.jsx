import { Label, Pie, PieChart } from 'recharts';
import { ChartContainer, ChartLegend, ChartLegendContent, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';

const config = {
    jumlah: { label: 'Warga' },
    laki: { label: 'Laki-laki', color: 'var(--chart-1)' },
    perempuan: { label: 'Perempuan', color: 'var(--chart-2)' },
};

/** Komposisi jenis kelamin, donat dengan total di tengah (shadcn "Pie Chart - Donut with Text"). */
export default function GrafikJenisKelamin({ laki = 0, perempuan = 0, tinggi = 280 }) {
    const data = [
        { jenis: 'laki', jumlah: laki, fill: 'var(--color-laki)' },
        { jenis: 'perempuan', jumlah: perempuan, fill: 'var(--color-perempuan)' },
    ];
    const total = laki + perempuan;

    return (
        <ChartContainer config={config} className="mx-auto aspect-auto w-full" style={{ height: tinggi }}>
            <PieChart>
                <ChartTooltip cursor={false} content={<ChartTooltipContent hideLabel nameKey="jenis" />} />
                <Pie data={data} dataKey="jumlah" nameKey="jenis" innerRadius="58%" outerRadius="85%" strokeWidth={4} paddingAngle={2}>
                    <Label
                        content={({ viewBox }) => {
                            if (viewBox && 'cx' in viewBox && 'cy' in viewBox) {
                                return (
                                    <text x={viewBox.cx} y={viewBox.cy} textAnchor="middle" dominantBaseline="middle">
                                        <tspan x={viewBox.cx} y={viewBox.cy} className="fill-foreground" style={{ fontSize: 28, fontWeight: 800 }}>
                                            {total.toLocaleString('id-ID')}
                                        </tspan>
                                        <tspan x={viewBox.cx} y={(viewBox.cy || 0) + 22} className="fill-muted-foreground" style={{ fontSize: 12 }}>
                                            Warga
                                        </tspan>
                                    </text>
                                );
                            }
                            return null;
                        }}
                    />
                </Pie>
                <ChartLegend content={<ChartLegendContent nameKey="jenis" />} />
            </PieChart>
        </ChartContainer>
    );
}
