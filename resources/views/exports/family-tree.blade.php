<!DOCTYPE html>
<html>

<head>
    <title>{{ $family->family_name }} - Family Tree</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .family-member {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .member-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .member-info {
            color: #666;
            margin-bottom: 3px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>{{ $family->family_name }} - Pohon Keluarga</h1>

    @if ($family->description)
        <div class="section">
            <p>{{ $family->description }}</p>
        </div>
    @endif

    <!-- Parents Section -->
    <div class="section">
        <div class="section-title">Orang Tua</div>
        @foreach ($familyMembers->where('relation', 'father')->merge($familyMembers->where('relation', 'mother')) as $member)
            <div class="family-member">
                <div class="member-name">{{ $member->name }}</div>
                <div class="member-info">
                    {{ $member->relation === 'father' ? 'Ayah' : 'Ibu' }} ·
                    {{ $member->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                </div>
                <div class="member-info">
                    Tanggal Lahir: {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}
                </div>
                @if ($member->description)
                    <div class="member-info">{{ $member->description }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Children Section -->
    <div class="section">
        <div class="section-title">Anak-anak</div>
        @foreach ($familyMembers->where('relation', 'child') as $member)
            <div class="family-member">
                <div class="member-name">{{ $member->name }}</div>
                <div class="member-info">
                    {{ $member->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                </div>
                <div class="member-info">
                    Tanggal Lahir: {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}
                </div>
                @if ($member->description)
                    <div class="member-info">{{ $member->description }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div style="text-align: center; margin-top: 40px; color: #666;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>
