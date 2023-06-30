<html>
<head>
  <style>
    body{
      font-family: sans-serif;
    }
    @page {
      margin: 160px 50px;
    }
    header { position: absolute;
      left: 0px;
      top: -160px;
      right: 0px;
      height: 100px;
      text-align: center;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }
    p {
        font-size: 13px;
    }
    ul {
        font-size: 13px;
    }

    span {
        font-size: 12px;
    }
  </style>


</head>
<body style="padding-left:5%;padding-right:5%;">
  <header>

  </header>
  <footer>
    <table>
      <tr>
        <td>
            <p class="izq">
              Dr. Rate
            </p>
        </td>
        <td>
          <p class="page">
            Página
          </p>
        </td>
      </tr>
    </table>
  </footer>
  <div id="content">
  <center>
    <h2>RESUMEN EJECUTIVO</h2>
    <h3>Análisis de estados financieros</h3>
    </center>

  <table style="width:100%;">
        <tr >
            <td style="width:25%;">Empresa:<br><br></td>
            <td>{{ $data['nombre_empresa'] }}<br><br></td>
        </tr>
        <tr>
            <td>País:<br><br></td>
            <td></td>
        </tr>
        <tr>
            <td>Ciudad o Estado:<br><br></td>
            <td></td>
        </tr>
    </table>
    <center>
    <br><br><br><br>
        <h3>Período de análisis</h3>
        <p style="text-align: justify;">{{ ucfirst($data['periodo_nombre']) }}: de {{ $data['periodo_actual'] }}</p>
    </center>
    <h4 style="page-break-before: always;">RESULTADOS OBTENIDOS</h4>

    <p style="text-align: justify;">La información financiera del negocio en el período analizado, ha conducido a la obtención de los siguientes resultados sobre sus ratios financieros:</p>

<br>
    <table style="width:100%;border: solid 1px black;">
        @php
            $indicator = [];
        @endphp
        @foreach($data['indicador'] as $item)
            @if($item->type == '2')
            <tr >
                <td style="border-bottom: solid 1px black;font-size: 13px;">{{ $item->name }}</td>
                <td style="border-bottom: solid 1px black;font-size: 13px;text-align: right;">{{ number_format(( $item->voiced == '2' ? (float)$item->result * 100 : (float)$item->result), 2) . ($item->voiced == '2' ? '%' : '') }}</td>
            </tr>
            @endif
            @php
                $indicator[$item->denomic] = ($item->voiced == '2' ? (float)$item->result * 100 : (float)$item->result);
                $indicator[$item->denomic.'_VALUE'] = number_format(($item->voiced == '2' ? (float)$item->result * 100 : (float)$item->result), 2) . ($item->voiced == '2' ? '%' : '');
            @endphp
        @endforeach
    </table>

    <p style="page-break-before: always;"><b>INTERPRETACIÓN DE LOS RATIOS FINANCIEROS Y SUGERENCIAS</b></p>
    <p style="text-align: justify;">A continuación, se explican los resultados de los ratios financieros de la empresa {{$data['nombre_empresa']}} y luego en cada uno se plantean estrategias y acciones para mejorar su desempeño.</p>

    <p style="text-align: justify;"><b>LIQUIDEZ</b></P>

    @if($indicator['RAZON_CORRIENTE'] < 1 &&  $indicator['PRUEBA_ACIDA'] < 1)
        <p style="text-align: justify;">Que la razón corriente de su negocio de {{ $indicator['RAZON_CORRIENTE_VALUE'] }} y su prueba ácida de {{ $indicator['PRUEBA_ACIDA_VALUE'] }}, hayan salido menores a 1, significa que su activo corriente total no cubre sus obligaciones o deudas de corto plazo, y esta situación empeora cuando no se toma en cuenta el valor de sus inventarios o existencias. Esta situación podría indicar que el negocio corre el riesgo de interrumpir su operaciones, o de agravar esta interrupción, por falta de liquidez, salvo si este problema no repercute en el deterioro de su rentabilidad (generalmente, a menor liquidez, mayor rentabilidad). Es decir, solo si esta desfavorable liquidez no es un obstáculo para la rentabilidad, no será un problema. De allí que si esta débil liquidez le está representando al negocio pérdidas de oportunidades de ingresos o de rentabilidad, está en la necesidad y urgencia de aumentarlo, pero sin incrementar costos, ya sea por pérdida de clientes, por mayor capital inmovilizado derivado de un mayor valor de inventarios o por otro motivo que perjudique los ingresos o la rentabilidad del negocio. En esta decisión, tome en cuenta que, si el costo de capital de los fondos de corto plazo de su negocio es superior que el gasto financiero de los fondos externos de corto plazo, será mejor usar más los fondos externos, pero bajo la condición de que esta medida no se traduzca en pérdidas de ingresos o de oportunidades de rentabilidad.</P>
    @endif

    @if($indicator['RAZON_CORRIENTE'] == 1 &&  $indicator['PRUEBA_ACIDA'] < 1)
        <p style="text-align: justify;">Que la razón corriente de su negocio sea igual a 1, y que su prueba ácida de {{ $indicator['PRUEBA_ACIDA_VALUE'] }}, haya salido menor a 1, significa que su activo corriente total cubre exactamente sus obligaciones o deudas de corto plazo, pero resulta insuficiente cuando no se toma en cuenta el valor de sus inventarios o existencias. Esta situación podría indicar que el negocio corre el riesgo de interrumpir su operaciones por falta de liquidez, salvo si este problema no repercute en el deterioro de su rentabilidad (generalmente, a menor liquidez, mayor rentabilidad). Es decir, si esta desfavorable liquidez no es un obstáculo para la rentabilidad, no será un problema. De allí que si esta débil liquidez le está representando al negocio pérdidas de oportunidades de ingresos o de rentabilidad, está en la necesidad y urgencia de aumentarlo, pero sin incrementar costos, ya sea por pérdida de clientes, por mayor capital inmovilizado derivado de un mayor valor de inventarios o por otro motivo que perjudique los ingresos o la rentabilidad del negocio. En esta decisión, tome en cuenta que, si el costo de capital de los fondos de corto plazo de su negocio es superior que el gasto financiero de los fondos externos de corto plazo, será mejor usar más los fondos externos, pero bajo la condición de que esta medida no se traduzca en pérdidas de ingresos o de oportunidades de rentabilidad.</P>
    @endif

    @if($indicator['RAZON_CORRIENTE'] > 1 &&  $indicator['PRUEBA_ACIDA'] > 1)
        <p style="text-align: justify;">Que la razón corriente de su negocio de {{ $indicator['RAZON_CORRIENTE_VALUE'] }} y su prueba ácida de {{ $indicator['PRUEBA_ACIDA_VALUE'] }}, hayan salido mayores a 1, significa que sus activos rápidamente realizables (o que prontiud se pueden convertir en efectivo) y sus activos no tan rápidamente realizables (por ejemplo, los inventarios) superan sus deudas de corto plazo. A pesar de que estos dos resultados  le dan tranquilidad, su negocio debe observar si esta favorable liquidez le está quitando oportunidades de aumentar su rentabilidad (generalmente, a mayor liquidez, menor rentabilidad). De allí que la clave radica en calcular cuánto una disminución de su liquidez impulsa su rentabilidad. Por ejemplo, si el gasto financiero que cobran los bancos es superior al costo de oportunidad del negocio, será conveniente aumentar el uso del efectivo del negocio; una disminución de las cuentas por cobrar se podría traducir en mayores ingresos en efectivo; o un menor valor de los inventarios significaría un menor costo del capital inmovilizado. Solo en el caso que la conservación o el aumento de los ratios de liquidez y de prueba rápida representaran oportunidades para aumentar sus ingresos, será recomendable decidirse por este camino. En estas decisiones, tome en cuenta que el límite de la disminución de la razón corriente será el punto a partir del cual se empiece a perder oportunidades de ingresos netos, y a su vez, ese esfuerzo deberá implantarse, siempre y cuando, el costo de capital (promedio ponderado del costo de oportunidad y de los gastos financieros) de los fondos internos de corto plazo sea mayor al gasto financiero de los fondos externos de corto plazo. Dicho de otro modo, si el costo de capital de los fondos de corto plazo de su negocio es superior que el gasto financiero de los fondos externos de corto plazo, será mejor usar más los fondos externos, pero bajo la condición de que esta medida no se traduzca en interrupciones de sus ingresos o en pérdidas de oportunidades de rentabilidad.</P>
    @endif

    @if($indicator['RAZON_CORRIENTE'] < 1 &&  $indicator['PRUEBA_ACIDA'] < 1)
        <p style="text-align: justify;">Que la razón corriente de su negocio de {{ $indicator['RAZON_CORRIENTE_VALUE'] }} sea mayor a 1, y su prueba ácida de {{ $indicator['PRUEBA_ACIDA_VALUE'] }}, haya salido menor a 1, significa que su activo corriente total cubre sus obligaciones o deudas de corto plazo, pero resulta insuficiente cuando no se toma en cuenta el valor de sus inventarios o existencias. Esta situación indica que la liquidez del negocio se sustenta en el importante valor de sus inventarios, y por consiguiente, para garantizar el cumplimiento de sus obligaciones o para asegurar la continuidad de sus operaciones corre el riesgo de posibles pérdidas por ventas o usos de sus inventarios a menor valor de lo comprado y además, tiene un alto costo de capital inmovilizado. Este resultado solo se justificaría si su alto valor en inventarios le representa beneficios por el posible aumento de los precios de las existencias en el futuro, o porque coloca al negocio en una posición ventajosa frente a sus competidores en cuanto calidad de servicio. De no existir esa justificación y con mayor razón si la desfavorable prueba ácida está repercutiendo negativamente en la rentabilidad o en el aprovechamiento de oportunidades de ingresos, obliga al negocio a reducir el valor de sus inventarios y a planificar y ejecutar acciones orientadas a mantener una liquidez favorable. Se le sugiere tomar en cuenta los siguiente: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">generalmente, a mayor liquidez, menor rentabilidad,</li>
                <li style="text-align: justify; font-size:14px">si el gasto financiero que cobran los bancos es superior al costo de oportunidad del negocio, será conveniente mantener el uso del efectivo del negocio,</li>
                <li style="text-align: justify; font-size:14px">una disminución de las cuentas por cobrar se podría traducir en mayores ingresos en efectivo,</li>
                <li style="text-align: justify; font-size:14px">un menor valor de los inventarios significaría un menor costo del capital inmovilizado, y</li>
                <li style="text-align: justify; font-size:14px">cualquier medida que decida no debe traducirse en pérdidas de ingresos o de oportunidades de rentabilidad.</li>
            </ol>
    @endif
@if($data['license']->plan != '1')
    <p style="text-align: justify;"><b>CICLO DE CAJA</b></P>

    @if($indicator['CICLO_DE_CAJA'] > 0)
        <p style="text-align: justify;">Que el ciclo de caja (período promedio de inventarios + período promedio de cobro - período promedio de pago) de su negocio haya sido positivo en {{$indicator['CICLO_DE_CAJA_VALUE']}} días, refleja que su negocio, cobra y rota sus inventarios más lento de lo que paga. En esta situación, uno de los aspectos más importantes a tomar en cuenta es si pagar rápido respecto a la lentitud de cobrar y vender los inventarios, resulta conveniente desde el punto de vista del costo del dinero externo e interno. Por lo tanto, el negocio debe evaluar si para lograr un cliclo de caja positivo, le resulta favorable sacrificar más fondos propios que fondos externos. Será asi, siempre y cuando el costo de los fondos propios (o costo de oportunidad) sea menor que el gasto financiero (o interés) de los fondos externos.</P>
    @endif

    @if($indicator['CICLO_DE_CAJA'] == 0)
        <p style="text-align: justify;">Que el ciclo de caja (período promedio de inventarios + período promedio de cobro - período promedio de pago) de su negocio sea neutro o igual a cero días, refleja que su negocio, cobra y rota sus existencias al mismo ritmo de lo que paga. Esta situación le será favorable al negocio  si el costo de los fondos propios (o costo de oportunidad) utililizados para honrar sus deudas es menor al gasto financiero (o interés) de estas.</P>
    @endif

    @if($indicator['CICLO_DE_CAJA'] < 0)
        <p style="text-align: justify;">Que el ciclo de caja (período promedio de inventarios + período promedio de cobro - período promedio de pago) de su negocio haya sido negativo en {{$indicator['CICLO_DE_CAJA_VALUE']}} días, refleja que su negocio, cobra y libera sus inventarios más rápido de lo que tarda en pagar a los proveedores. En esta situación, uno de los aspectos más importantes a considerar es si la mayor rapidez en la cobranza y en la rotación de los inventarios respecto a la velocidad de pago, resulta conveniente desde el punto de vista del costo del dinero externo e interno. En consecuencia, el negocio debe evaluar si para lograr un ciclo de caja negativo, le resulta favorable recurrir más a fondos externos que a fondos propios. Será asi, siempre y cuando el costo de los fondos propios (o costo de oportunidad) sea mayor que el gasto financiero (o interés) de los fondos externos.</P>
    @endif



    <p style="text-align: justify;"><b>COSTO DE CAPITAL</b></P>

    @if($indicator['TASA_MES_ACTUAL_GF'] > $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">Considerando que el costo de capital es el promedio ponderado del costo de oportunidad (costo de los fondos propios) y el gasto financiero (interés de los fondos externos), que el gasto financiero de {{$indicator['TASA_MES_ACTUAL_GF_VALUE']}} sea mayor a su costo de oportunidad de {{$indicator['TASA_MES_ACTUAL_CO_VALUE']}}, le aconseja que debe hacer todo lo posible para minimizar el uso de fondos externos y maximizar el uso de sus fondos propios. En esta medida, tome en cuenta que el límite de la utilización de sus fondos es el momento a partir del cual se pone en peligro la paralización de las operaciones del negocio por falta de fondos propios.</P>
    @endif

    @if($indicator['TASA_MES_ACTUAL_GF'] == $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">Considerando que el costo de capital es el promedio ponderado del costo de oportunidad (costo de los fondos propios) y el gasto financiero (interés de los fondos externos), que el gasto financiero de {{$indicator['TASA_MES_ACTUAL_GF_VALUE']}} sea igual que su costo de oportunidad de {{$indicator['TASA_MES_ACTUAL_CO_VALUE']}}, le aconseja que, en términos de costo, le da lo mismo emplear fondos externos o sus fondos propios. En este caso, la decisión de cuál de esos dos fondos utilizar en mayor proporción, depende de la urgencia en cuanto a disponibilidad de los fondos para elevar la rentabilidad del negocio.</P>
    @endif

    @if($indicator['TASA_MES_ACTUAL_GF'] < $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">Considerando que el costo de capital es el promedio ponderado del costo de oportunidad (costo de los fondos propios) y el gasto financiero (interés de los fondos externos), que el gasto financiero de {{$indicator['TASA_MES_ACTUAL_GF_VALUE']}} sea menor al costo de oportunidad de {{$indicator['TASA_MES_ACTUAL_CO_VALUE']}}, le aconseja que debe hacer todo lo posible para maximizar el uso de fondos externos y minimizar el uso de sus fondos propios. En esta medida, tome en cuenta que el límite de la utilización de los fondos externos es el momento a partir del cual se deteriora su solvencia, al punto de reducir su poder de negociación ante los proveedores de fondos externos.</P>
    @endif

    <p style="text-align: justify;"><b>RAZÓN DE APALANCAMIENTO</b></P>

    @if($indicator['APALANCAMIENTO'] > 0.7)
        <p style="text-align: justify;">La razón de apalancamiento es una medida de la solvencia del negocio que señala cuánto de la inversión total del negocio es financiada por agentes externos. Se calcula dividiendo el pasivo total entre el activo total. En vista que la razón de apalancamiento del negocio de {{$indicator['APALANCAMIENTO_VALUE']}} es alta, pues indica que el {{($indicator['APALANCAMIENTO_VALUE'] * 100)}}% de su activo total es deuda, para mejorar la efectividad de la gestión de su solvencia, el negocio debe: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">estimar la razón de apalancamiento a partir de la cual empezaría a perder poder de negociación y prever estrategias y acciones para evitar que el negocio sobrepase ese punto; y si lo ha sobrepasado, planificar y ejecutar acciones para reducir su nivel de apalancamiento y, en especial, para disminuir los gastos financieros de sus deudas,</li>
                <li style="text-align: justify; font-size:14px">planificar y ejecutar acciones encaminadas a garantizar el pago de sus deudas,</li>
                <li style="text-align: justify; font-size:14px">conseguir fondos con intereses más bajos y de mayor plazo de pago,</li>
                <li style="text-align: justify; font-size:14px">prescindir de las deudas más onerosas,</li>
                <li style="text-align: justify; font-size:14px">prescindir de las deudas más onerosas,</li>
                <li style="text-align: justify; font-size:14px">programar y poner en marcha medidas que mantengan o aumenten las utilidades de modo sostenible, para de este modo, honrar el pago de sus deudas, y</li>
                <li style="text-align: justify; font-size:14px">designar formalmente, el equipo encargado de la ejecución, seguimiento y control de las estrategias y acciones acordadas.</li>
            </ol>
    @endif

    @if(0.7 > $indicator['APALANCAMIENTO'] && $indicator['APALANCAMIENTO'] >= 0.6)
        <p style="text-align: justify;">La razón de apalancamiento es una medida de la solvencia del negocio que señala cuánto de la inversión total del negocio es financiada por agentes externos. Se calcula dividiendo el pasivo total entre el activo total. En vista que la razón de apalancamiento del negocio de {{$indicator['APALANCAMIENTO_VALUE']}} es media, pues señala que el {{($indicator['APALANCAMIENTO_VALUE'] * 100)}}% de su activo total es deuda, en esta situación más que enfocarse en cómo evitar la pérdida de poder de negociación frente a los proveedores de fondos externos, importa: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">definir qué hacer para obtener fondos externos con intereses más bajos y plazos más largos, para reemplazar a los fondos externos con intereses más altos y plazos más cortos,</li>
                <li style="text-align: justify; font-size:14px">estimar la razón de apalancamiento a partir de la cual el negocio empezaría a perder poder de negociación frente a los proveedores de fondos externos, y</li>
                <li style="text-align: justify; font-size:14px">considerando que el negocio por poseer un nivel de apalancamiento medio, todavía tiene una brecha para endeudarse, identificar oportunidades que podrían aprovecharse empleando ese posible mayor fondeo externo.</li>
            </ol>
    @endif

    @if($indicator['APALANCAMIENTO'] < 0.6)
        <p style="text-align: justify;">La razón de apalancamiento es una medida de la solvencia del negocio que señala cuánto de la inversión total del negocio es financiada por agentes externos. Se calcula dividiendo el pasivo total entre el activo total. En vista que la razón de apalancamiento del negocio de {{$indicator['APALANCAMIENTO_VALUE']}} es baja, pues indica que solo el {{($indicator['APALANCAMIENTO_VALUE'] * 100)}}% de su activo total está comprometido con obligaciones, en esta situación el negocio debiera enfocarse en la búsqueda de oportunidades para aumentar su rentabilidad, utilizando como impulsor el aumento de su razón de apalancamiento. Si bien, por su baja razón de apalancamiento, el negocio tiene una brecha para endeudarse, debe evitar que ese mayor endeudamiento represente altos intereses y plazos cortos y no debe sobrepasar ese nivel de endeudamiento a partir del cual empezaría a perder poder de negociación frente a sus proveedores de fondos externos.</P>
    @endif

    <p style="text-align: justify;"><b>RELACIÓN DEUDA/CAPITAL</b></P>

    @if($indicator['RELACION_DEUDA'] >= 2.33)
        <p style="text-align: justify;">La relación deuda/capital o deuda/patrimonio es un indicador de la solvencia del negocio que expresa la cantidad de unidades monetarias de fondos externos por cada unidad monetaria de capital propio que están financiando la inversión total del negocio. Se calcula dividiendo el pasivo total entre el patrimonio. Por cuanto la relación deuda/capital de {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} es alta, pues significa que la deuda total es {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} veces el patrimonio o capital del negocio, este ratio financiero indica que el negocio presenta una fuerte dependencia financiera. En esta situación, para mejorar la gestión de su solvencia, el negocio debe: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">estimar la relación deuda/capital a partir de la cual empezaría a perder poder de negociación y prever estrategias y acciones para evitar que el negocio sobrepase ese punto; y si lo ha sobrepasado, planificar y ejecutar acciones para reducir su relación deuda/capital y, en particular, para disminuir los gastos financieros de sus deudas,</li>
                <li style="text-align: justify; font-size:14px">planificar y ejecutar acciones encaminadas a garantizar el pago de sus deudas,</li>
                <li style="text-align: justify; font-size:14px">conseguir fondos con intereses más bajos y de mayor plazo de pago,</li>
                <li style="text-align: justify; font-size:14px">prescindir de las deudas más onerosas,</li>
                <li style="text-align: justify; font-size:14px">programar y poner en marcha medidas que mantengan o aumenten las utilidades de modo sostenible, para de este modo, honrar el pago de sus deudas, y</li>
                <li style="text-align: justify; font-size:14px">designar formalmente, el equipo encargado de la ejecución, seguimiento y control de las estrategias y acciones acordadas.</li>
            </ol>
    @endif

    @if(1.5 < $indicator['RELACION_DEUDA'] && $indicator['RELACION_DEUDA'] < 2.33)
        <p style="text-align: justify;">La relación deuda/capital o deuda/patrimonio es un indicador de la solvencia del negocio que expresa la cantidad de unidades monetarias de fondos externos por cada unidad monetaria de capital propio que están financiando la inversión total del negocio. Se calcula dividiendo el pasivo total entre el patrimonio. Por cuanto la relación deuda/capital de {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} es media, pues significa que la deuda total es {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} veces el patrimonio o capital del negocio, este ratio financiero indica que el negocio presenta una regular dependencia financiera. En esta situación, para elevar su efectividad en el manejo de su solvencia, es importante que el negocio realice lo siguiente: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">definir qué hacer para obtener fondos externos con intereses más bajos y plazos más largos, para reemplazar a los fondos externos con intereses más altos y plazos más cortos,</li>
                <li style="text-align: justify; font-size:14px">estimar la relación deuda/patrimonio a partir de la cual el negocio empezaría a perder poder de negociación frente a los proveedores de fondos externos, y</li>
                <li style="text-align: justify; font-size:14px">considerando que el negocio por poseer una relación deuda/capital de nivel medio, todavía tiene una brecha para endeudarse, identificar oportunidades que podrían aprovecharse empleando ese posible mayor fondeo externo.</li>
            </ol>
     @endif

    @if($indicator['RELACION_DEUDA'] < 1.5)
        <p style="text-align: justify;">La relación deuda/capital o deuda/patrimonio es un indicador de la solvencia del negocio que expresa la cantidad de unidades monetarias de fondos externos por cada unidad monetaria de capital propio que están financiando la inversión total del negocio. Se calcula dividiendo el pasivo total entre el patrimonio. Por cuanto la relación deuda/capital de {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} es baja, pues significa que la deuda total es {{($indicator['RELACION_DEUDA_VALUE'] * 100)}} veces el patrimonio o capital del negocio, este ratio financiero indica que el negocio presenta una baja dependencia financiera. En esta situación el negocio debiera enfocarse en la búsqueda de oportunidades para aumentar su rentabilidad, utilizando como impulsor el aumento de su relación deuda/capital, o que es lo mismo, el incremento de su grado de dependencia financiera. Si bien, por su baja relación deuda/capital, el negocio tiene una brecha para endeudarse, debe evitar que ese mayor endeudamiento represente altos intereses y plazos cortos y no debe sobrepasar ese nivel de endeudamiento a partir del cual empezaría a perder poder de negociación frente a sus proveedores de fondos externos.</P>
    @endif

    <p style="text-align: justify;"><b>MÁRGENES DE UTILIDAD</b></p>

    @if($indicator['MARGEN_UT_BRUTA'] > 0 && $indicator['MARGEN_UT_OPERATIVA'] > 0 && $indicator['MARGEN_UT_NETA'] > 0)
        <p style="text-align: justify;">El margen de utilidad bruta se calcula dividiendo la utilidad bruta entre las ventas netas, el margen de utilidad operativa resulta de dividir la utilidad operativa entre las ventas netas, y el margen de utilidad neta se estima dividiendo la utilidad neta entre las ventas del negocio. Los márgenes de utilidad bruta, utilidad operativa y utilidad neta de {{($indicator['MARGEN_UT_BRUTA_VALUE'] )}}%, {{($indicator['MARGEN_UT_OPERATIVA_VALUE'] )}}% y {{($indicator['MARGEN_UT_NETA_VALUE'] )}}%, respectivamente, son todos positivos. De manera general, estos resultados conducen a que el negocio ponga en marcha una combinación de las estrategias de consolidación o KANRI y KAIZEN o mejora continua. Entonces, básicamente debe: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">diseñar diseñar e instalar sistemas o poner cuñas que eviten que el ingreso, el costo de ventas y los gastos administrativos, de marketing, de venta, de distribución y financieros, varíen desfavorablemente; y</li>
                <li style="text-align: justify; font-size:14px">planificar y ejecutar medidas que lo consolidado mejore su desempeño.</li>
            </ol>
    @elseif($indicator['MARGEN_UT_BRUTA'] < 0 && $indicator['MARGEN_UT_OPERATIVA'] < 0 && $indicator['MARGEN_UT_NETA'] < 0)
        <p style="text-align: justify;">El margen de utilidad bruta se calcula dividiendo la utilidad bruta entre las ventas netas, el margen de utilidad operativa resulta de dividir la utilidad operativa entre las ventas netas, y el margen de utilidad neta se estima dividiendo la utilidad neta entre las ventas del negocio. Los márgenes de utilidad bruta, utilidad operativa y utilidad neta de {{($indicator['MARGEN_UT_BRUTA_VALUE'] )}}%, {{($indicator['MARGEN_UT_OPERATIVA_VALUE'] )}}% y {{($indicator['MARGEN_UT_NETA_VALUE'] )}}%, respectivamente, son todos negativos. Estos pésimos resultados obligan al negocio a implementar la estrategia de transformación, la cual, de manera general, comprende: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">redefinir la meta más importante del negocio,</li>
                <li style="text-align: justify; font-size:14px">redefinir los productos o servicios con nuevas funciones esenciales y cadenas de valor, y</li>
                <li style="text-align: justify; font-size:14px">replantear y renovar conocimientos, prejuicios o marcos de gestión, valores morales, creencias y hábitos.</li>
            </ol>
    @elseif($indicator['MARGEN_UT_BRUTA'] > 0 && $indicator['MARGEN_UT_OPERATIVA'] < 0 && $indicator['MARGEN_UT_NETA'] > 0)
        <p style="text-align: justify;">El margen de utilidad bruta se calcula dividiendo la utilidad bruta entre las ventas netas, el margen de utilidad operativa resulta de dividir la utilidad operativa entre las ventas netas, y el margen de utilidad neta se estima dividiendo la utilidad neta entre las ventas del negocio. Los márgenes de utilidad bruta, utilidad operativa y utilidad neta de {{($indicator['MARGEN_UT_BRUTA_VALUE'] )}}%, {{($indicator['MARGEN_UT_OPERATIVA_VALUE'] )}}% y {{($indicator['MARGEN_UT_NETA_VALUE'] )}}%, respectivamente, señalan que el negocio genera utilidad hasta su proceso de producción, pero se pierde con los gastos administrativos, gastos de marketing, gastos de venta y gastos de distribución. Asimismo, es probable que por ingresos no propios de la naturaleza del negocio, presente utilidad neta positiva. En esta situación, el negocio debe planificar y ejecutar estrategias y acciones encaminadas a elevar la productividad de las áreas de administración, marketing, ventas y distribución, y también para incrementar su utilidad bruta.</P>
    @elseif($indicator['MARGEN_UT_BRUTA'] > 0 && $indicator['MARGEN_UT_OPERATIVA'] > 0 && $indicator['MARGEN_UT_NETA'] < 0)
        <p style="text-align: justify;">El margen de utilidad bruta se calcula dividiendo la utilidad bruta entre las ventas netas, el margen de utilidad operativa resulta de dividir la utilidad operativa entre las ventas netas, y el margen de utilidad neta se estima dividiendo la utilidad neta entre las ventas del negocio. Los márgenes de utilidad bruta, utilidad operativa y utilidad neta de {{($indicator['MARGEN_UT_BRUTA_VALUE'] )}}%, {{($indicator['MARGEN_UT_OPERATIVA_VALUE'] )}}% y {{($indicator['MARGEN_UT_NETA_VALUE'] )}}%, respectivamente, señalan que el negocio genera utilidad bruta y utilidad operativa, pero son superadas, probablemente, por sus gastos financieros y otros gastos no propios de la naturaleza del negocio. En esta situación, el negocio debe planificar y ejecutar estrategias y acciones encaminadas a solucionar sus problemas de caracter financiero o relacionados a actividades que no corresponden a sus funciones permanentes.</P>
    @else
        <p style="text-align: justify;">El margen de utilidad bruta se calcula dividiendo la utilidad bruta entre las ventas netas, el margen de utilidad operativa resulta de dividir la utilidad operativa entre las ventas netas, y el margen de utilidad neta se estima dividiendo la utilidad neta entre las ventas del negocio. Los márgenes de utilidad bruta, utilidad operativa y utilidad neta de {{($indicator['MARGEN_UT_BRUTA_VALUE'] )}}%, {{($indicator['MARGEN_UT_OPERATIVA_VALUE'] )}}% y {{($indicator['MARGEN_UT_NETA_VALUE'] )}}%, respectivamente, indican un desempeño rarísimo o confuso. Esto podría haberse ocasionado por un mal cálculo de los márgenes. Si los resultados reflejaran la realidad, el negocio deberá planificar y ejecutar estrategias y acciones no convencionales para lograr márgenes positivos de utilidad bruta, utilidad operativa y utilidad neta.</P>
    @endif

    <p style="text-align: justify;"><b>RETORNO SOBRE LA INVERSIÓN (ROI)</b></p>

    @if($indicator['ROI'] > $indicator['TASA_MES_ACTUAL_CC'])
        <p style="text-align: justify;">El término ""ROI"" proviene de las palabras en inglés return on investment. Mide el rendimiento de la inversión total. Se obtiene dividiendo la utilidad neta entre el activo total o la inversión total. En el período analizado, que el ROI del negocio de {{($indicator['ROI_VALUE'] )}}% sea mayor al costo de capital de {{($indicator['TASA_MES_ACTUAL_CC_VALUE'] )}}%, significa que el negocio o la empresa es rentable. Sin embargo, no debería conformarse con este resultado. En su lugar, debe constituir un equipo estratega que se encargue de formular y ejecutar las siguientes estrategias y acciones básicas: </P>
            <ol type="a">
                <li style="text-align: justify; font-size:14px">identificar y superar los cuellos de botella que todavía se presentan, y</li>
                <li style="text-align: justify; font-size:14px">consolidar lo bueno que se está haciendo en cuanto a los buenos resultados en los indicadores de utilidad y de apalancamiento de los activos más importantes.</li>
            </ol>
    @endif

    @if($indicator['ROI'] == $indicator['TASA_MES_ACTUAL_CC'])
        <p style="text-align: justify;">El término ""ROI"" proviene de las palabras en inglés return on investment. Mide el rendimiento de la inversión total. Se obtiene dividiendo la utilidad neta entre el activo total o la inversión total. En el período analizado, que el ROI del negocio de {{($indicator['ROI_VALUE'] )}}% sea igual al costo de capital de {{($indicator['TASA_MES_ACTUAL_CC_VALUE'] )}}%, significa que el negocio o la empresa no es rentable, pero tampoco no rentable, pues su utilidad neta cubre exactamente la ganancia mínima esperada por su activo total invertido. Este resultado debe servir de pretexto para identificar y corregir lo que el negocio podría hacer mejor y detectar y sistematizar y mejorar lo que estaría haciendo bien.</P>
    @endif

    @if($indicator['ROI'] < $indicator['TASA_MES_ACTUAL_CC'])
        <p style="text-align: justify;">El término ""ROI"" proviene de las palabras en inglés return on investment. Mide el rendimiento de la inversión total. Se obtiene dividiendo la utilidad neta entre el activo total o la inversión total. En el período analizado, que el ROI del negocio de {{($indicator['ROI_VALUE'] )}}% sea menor al costo de capital de {{($indicator['TASA_MES_ACTUAL_CC_VALUE'] )}}%, significa que el negocio o la empresa no es rentable, pues su utilidad neta no cubre ni la ganancia mínima esperada por su activo total invertido. Este resultado es materia de preocupación. Sin embargo antes de tomar alguna decisión, el negocio debe estimar el nivel de gravedad de su estado de salud. Si la enfermedad es leve, necesitará formular y poner en marcha estrategias y acciones que mejoren gradualmente su desempeño. En el caso que el estado de salud resulte grave, deberá tomar decisiones que cuestionen determinadas actividades de la cadena de valor actual y probablemente conduzcan a eliminaciones de aquellas que no le están generando valor al negocio y tampoco a sus clientes. Cuando el estado de salud amerite ser calificado como moribundo, el negocio tendrá que llenarse de inteligencia y coraje para transformarse y emprender decisiones de redefinición del posicionamiento de marca, concepto o cuento del negocio y también de la configuración de su cadena de valor.</P>
    @endif

    <p style="text-align: justify;"><b>RETORNO SOBRE EL PATRIMONIO (ROE)</b></p>

    @if($indicator['ROE'] > $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">El término ""ROE"" procede de sus siglas en inglés que significan return on equity. Calcula el rendimiento del patrimonio o del capital invertido por los accionistas. Resulta de la división de la utilidad neta entre el patrimonio. En el período analizado, que el ROI del negocio de {{($indicator['ROE_VALUE'] )}}% sea mayor al costo de oportunidad de {{($indicator['TASA_MES_ACTUAL_CO_VALUE'] )}}%, significa que el negocio es rentable para sus accionistas. Sin embargo, no debería conformarse con este resultado. En su lugar, debe realizar estudios orientados a examinar la sostenibilidad, tanto del atractivo del sector como del desempeño interno del negocio, para en función de los resultados que se encuentren, tomar decisiones encaminadas a incrementar o disminuir el capital invertido y a elevar la utilidad neta.</P>
    @endif

    @if($indicator['ROE'] == $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">El término ""ROE"" procede de sus siglas en inglés que significan return on equity. Calcula el rendimiento del patrimonio o del capital invertido por los accionistas. Resulta de la división de la utilidad neta entre el patrimonio. En el período analizado, que el ROI del negocio de {{($indicator['ROE_VALUE'] )}}% sea igual al costo de oportunidad de {{($indicator['TASA_MES_ACTUAL_CO_VALUE'] )}}%, significa que el negocio no es rentable para sus accionistas, pero tampoco no rentable para ellos, pues su utilidad neta cubre exactamente la ganancia mínima esperada por el capital invertido por sus accionistas. Este resultado debe servir de pretexto para identificar y corregir lo que el negocio podría hacer mejor, detectar y sistematizar y mejorar lo que estaría haciendo bien y buscar nuevas fuentes de ingresos o nuevas formas de obtener mayor productividad con el mismo capital invertido por los accionistas.</P>
    @endif

    @if($indicator['ROE'] < $indicator['TASA_MES_ACTUAL_CO'])
        <p style="text-align: justify;">El término ""ROE"" procede de sus siglas en inglés que significan return on equity. Calcula el rendimiento del patrimonio o del capital invertido por los accionistas. Resulta de la división de la utilidad neta entre el patrimonio. En el período analizado, que el ROI del negocio de {{($indicator['ROE_VALUE'] )}}% sea menor al costo de oportunidad de {{($indicator['TASA_MES_ACTUAL_CO_VALUE'] )}}%, significa que el negocio no es rentable para sus accionistas, pues su utilidad neta no cubre ni la ganancia mínima esperada por el capital invertido por sus propietarios. Este resultado es materia de preocupación. No obstante, debe evitarse la tentación comprensible de los accionistas de desinvertir. En este sentido, el negocio debe estimar el nivel de gravedad de su estado de salud. Si la enfermedad es leve, necesitará formular y poner en marcha estrategias y acciones que mejoren gradualmente su desempeño. En el caso que el estado de salud resulte grave, deberá tomar decisiones que cuestionen determinadas actividades de la cadena de valor actual y probablemente conduzcan a eliminaciones de aquellas que no le están generando valor al negocio y tampoco a sus clientes. Cuando el estado de salud amerite ser calificado como moribundo, los líderes del negocio deberán persuadir a los accionstas para seguir invirtiendo y emprender la estrategia de transformación. Ese drástico camino exige llenarse de inteligencia y coraje para transformarse y emprender decisiones de redefinición del posicionamiento de marca, concepto o cuento del negocio y también de la configuración de su cadena de valor.</P>
    @endif

    <p style="text-align: justify;"><b>INGRESO NETO DEL FLUJO MONETARIO</b></p>

    @if($indicator['INGRESO_NETO_FLUJO_M'] > 0)
        <p style="text-align: justify;">
Es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. Que el ingreso neto del Flujo monetario del período actual frente al período anterior sea de {{($indicator['INGRESO_NETO_FLUJO_M_VALUE'] )}} {{$data['periodo_nombre']}}, o haya resultado positivo o mayor a cero, califica de manera favorable la gestión de los movimientos de dinero correspondientes a los ingresos, los gastos y los inventarios en los períodos consecutivos comparados. De manera general, este resultado obliga al negocio a identificar las razones por las cuales ha conseguido ese buen desempeño y a tomar medidas que eviten que ese resultado decline. En consecuencia, la empresa para continuar en ese buen camino, deberá consolidar o sistematizar lo que viene realizando en lo concerniente a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Será conveniente, que el negocio ejecute, básicamente, lo siguiente:
    </P>
    <ol type="a">
        <li style="text-align: justify; font-size:14px">Determinar las causas por las cuales se ha obtenido un ingreso neto positivo en el Flujo Monetario.</li>
        <li style="text-align: justify; font-size:14px">Identificar los costos y gastos más importantes.</li>
        <li style="text-align: justify; font-size:14px">Identificar los inventarios de mayor valor monetario.</li>
        <li style="text-align: justify; font-size:14px">Elegir y llevar a cabo estrategias o acciones:
            <ul>
                <li style="text-align: justify; font-size:14px">orientadas a que las causas por las cuales se ha logrado este buen resultado, se fortalezcan y repitan de manera sostenida, y</li>
                <li style="text-align: justify; font-size:14px">enfocadas en el mantenimiento o disminución de los costos y gastos e inventarios de mayor valor monetario.</li>
            </ul>
        </li>
    </ol>
    @endif

    @if($indicator['INGRESO_NETO_FLUJO_M'] == 0)
        <p style="text-align: justify;">
Es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. Que el ingreso neto del Flujo monetario del período actual frente al período anterior sea de {{($indicator['INGRESO_NETO_FLUJO_M_VALUE'] )}} {{$data['periodo_nombre']}}, o haya resultado neutro o igual a cero, indica que la gestión de los movimientos de dinero correspondientes a los ingresos, los gastos y los inventarios en los períodos consecutivos comparados, ofrece oportunidades de mejora. De manera general, este resultado obliga al negocio a identificar las razones de los buenos y también de los malos resultados y a tomar medidas que eviten que los buenos resultados se debiliten y que superen los malos resultados. Entonces, para que el negocio salga de la zona de indiferencia y se ubique en un espacio que indique una gestion favorable de su Flujo Monetario, deberá consolidar o sistematizar lo que viene realizando bien y rehacer lo que está haciendo mal en torno a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Será conveniente, que el negocio ejecute, mínimamente, lo siguiente:
    </P>
    <ol type="a">
        <li style="text-align: justify; font-size:14px">Determinar las causas por las cuales se han hecho algunas cosas bien.</li>
        <li style="text-align: justify; font-size:14px">Determinar las causas por las cuales algunas cosas no se han hecho bien.</li>
        <li style="text-align: justify; font-size:14px">Identificar los costos y gastos más importantes.</li>
        <li style="text-align: justify; font-size:14px">Identificar los inventarios de mayor valor monetario.</li>
        <li style="text-align: justify; font-size:14px">Elegir y llevar a cabo estrategias o acciones enfocadas en la superación de las causas identificadas y en los costos, gastos e inventarios más relevantes.</li>
    </ol>

    @endif

    @if($indicator['INGRESO_NETO_FLUJO_M'] < 0)
        <p style="text-align: justify;">
Es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. Que el ingreso neto del Flujo monetario del período actual frente al período anterior sea de {{($indicator['INGRESO_NETO_FLUJO_M_VALUE'] )}} {{$data['periodo_nombre']}}, o haya resultado negativo o menor a cero, califica de manera desfavorable la gestión de los movimientos de dinero correspondientes a los ingresos, los gastos y los inventarios en los períodos consecutivos comparados, y advierte la urgencia de revertir esta situación. De manera general, este resultado obliga al negocio a identificar las razones de ese mal desempeño y a tomar medidas que curen sus problemas y sus respectivas causas. De allí que, dependiendo de la gravedad de su estado de salud, el negocio debe elegir entre las opciones de emprender iniciativas de mejora gradual, mejora radical, transformación o de una combinación de las medidas anteriores. En esta tarea debe entenderse que la mejora gradual implica realizar cambios en la actual cadena de valor; y que la mejora radical consiste en eliminar actividades o tareas que no sirvan a los clientes ni al negocio o agregar actividades o tareas que sean útiles para los clientes, pero manteniendo las actividades de la cadena de valor del negocio. La estrategia de transformación, se elige cuando los productos no cumplen sus funciones esenciales y las actividades presentan un pésimo desempeño y comprende básicamente:

 </P>
    <ol type="a">
        <li style="text-align: justify; font-size:14px">redefinir la meta más importante del negocio,</li>
        <li style="text-align: justify; font-size:14px">redefinir los productos o servicios con nuevas funciones esenciales y cadenas de valor, y</li>
        <li style="text-align: justify; font-size:14px">replantear y renovar conocimientos, prejuicios o marcos de gestión, valores morales, creencias y hábitos. En cualquiera de los casos, las acciones que se adopten deben mejorar el desempeño en lo concerniente a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Por consiguiente, el negocio para abandonar la zona de mal desempeño y ubicarse en un espacio que indique una gestion favorable de su Flujo Monetario, deberá ejecutar, urgente y básicamente, lo siguiente:</li>
        <ul>
            <li style="text-align: justify; font-size:14px">Buscar explicaciones de la evolución de los ingresos en sus variables específicas: precio y volumen. Recuerde que mientras más grave es el problema, el análisis debe ser más detallado.</li>
            <li style="text-align: justify; font-size:14px">Descubrir por qué no se están logrando variaciones positivas en los costos de ventas, los gastos administrativos, los gastos de venta y el costo de capital de los inventarios, particularmente en aquellos rubros de mayor valor monetario.</li>
            <li style="text-align: justify; font-size:14px">IElegir y llevar a cabo estrategias y acciones enfocadas en la superación de las causas identificadas y en los costos, gastos e inventarios más relevantes.</li>
        </ul>
    </ol>

    @endif

    @endif
    <p ><b>RECOMENDACIÓN GENERAL</b></p>
    <p style="text-align: justify;">De manera general, nunca se guíe por el resultado de un solo ratio u observando tan solo el resultado o efecto. Por el contrario, trate de observar el bosque y no solo el árbol, así como las causas y los efectos de uno y otro ratio, y los motivos y las consecuencias al interior de cada ratio. Un buen ratio, puede haber sido efecto de un mal desempeño en otro, como por ejemplo, cuando aumenta la razón corriente como consecuencia de la disminución de la rentabilidad, o si la razón corriente sube como fruto del incremento de las cuentas por cobrar. Puede haber disminuido el efectivo y esa variación puede estar explicada por el aumento de las cuentas por cobrar, pero al mismo tiempo esas fluctuaciones pueden haberse convertido en mayor ingresos o ventas netas. Es decir, lo que está encima comúnmente no explica lo que ocurre por debajo y por lo tanto solo indica el estado de salud parcial de su negocio. Entonces, si un indicador de liquidez, solvencia, gestión, rentabilidad o flujo monetario, aumentó, no necesariamente será bueno o malo, o de haber sucedido lo contrario, tampoco será malo ni bueno. Al final, lo que le debe interesar es que el conjunto de ratios se comporten en favor de la rentabilidad sostenida de la organización.</p>

</body>
</html>

