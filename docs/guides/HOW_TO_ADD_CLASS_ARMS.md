# HOW TO ADD CLASS ARMS - STEP BY STEP

## Step 1: Go to Classes Management

1. Open your browser
2. Navigate to your SMS: `http://localhost/SMS_Project/public` or `http://localhost:8000`
3. Click on **"Classes"** in the sidebar menu

## Step 2: Create a Class (If you haven't already)

On the left side, you'll see a form:

```
Create Class
━━━━━━━━━━━━━━━━━━━━━━
Class Name: [JSS1      ]
Level:      [JSS1 ▼    ]
Description: [optional ]
           [Save Class]
```

Fill it in:
- **Class Name:** JSS1
- **Level:** Select JSS1 from dropdown
- **Description:** Junior Secondary School 1 (optional)
- Click **"Save Class"**

## Step 3: Add Arms to Your Class

After creating a class, you'll see it as a card on the right side:

```
┌────────────────────────────────┐
│ JSS1    JSS1                  │
│ Junior Secondary School 1      │
│                                │
│ Arms (Total):      0           │
│ Students (Total):  0           │
│                                │
│        [+ Add Arm]  ← CLICK HERE!
└────────────────────────────────┘
```

**Click the green "+ Add Arm" button**

## Step 4: Fill in the Arm Details

A popup window will appear:

```
┌─────────────────────────────────┐
│ Add Arm to JSS1              [×]│
├─────────────────────────────────┤
│                                 │
│ Arm Name: [A              ]     │
│ Usually: A, B, C, D             │
│                                 │
│ Capacity: [40             ]     │
│ Recommended: 35-45 students     │
│                                 │
│ Teacher:  [Select Teacher ▼]    │
│                                 │
│  [Cancel]        [Add Arm]      │
└─────────────────────────────────┘
```

Fill it in:
- **Arm Name:** A (or B, C, D, etc.)
- **Capacity:** 40 (how many students can be in this arm)
- **Teacher:** Select a teacher (optional)
- Click **"Add Arm"**

## Step 5: Add More Arms

Repeat Step 3 and 4 to add more arms:
- Click "+ Add Arm" again
- Enter: **B**, Capacity: **40**
- Click "+ Add Arm" again  
- Enter: **C**, Capacity: **35**

## Step 6: See Your Arms

Now your class card will show:

```
┌────────────────────────────────┐
│ JSS1    JSS1                  │
│ Junior Secondary School 1      │
│                                │
│ Arms (Total):      3           │
│ Students (Total):  0           │
│                                │
│        [+ Add Arm]             │
│                                │
│ Class Arms                     │
│ • A (0/40)  Mr. Adebayo        │
│ • B (0/40)  Mrs. Okonkwo       │
│ • C (0/35)  No Teacher         │
└────────────────────────────────┘
```

## Complete Example: Setting Up All Classes

### Create JSS1 with 3 arms:
1. Create Class: JSS1
2. Add Arm: A, Capacity 40
3. Add Arm: B, Capacity 40
4. Add Arm: C, Capacity 35

### Create JSS2 with 3 arms:
1. Create Class: JSS2
2. Add Arm: A, Capacity 40
3. Add Arm: B, Capacity 40
4. Add Arm: C, Capacity 35

### Create JSS3 with 2 arms:
1. Create Class: JSS3
2. Add Arm: A, Capacity 40
3. Add Arm: B, Capacity 40

### Create SS1 with 3 arms (specialized):
1. Create Class: SS1
2. Add Arm: Science, Capacity 30
3. Add Arm: Arts, Capacity 30
4. Add Arm: Commercial, Capacity 30

### Create SS2:
1. Create Class: SS2
2. Add Arm: A, Capacity 35
3. Add Arm: B, Capacity 35

### Create SS3:
1. Create Class: SS3
2. Add Arm: A, Capacity 30
3. Add Arm: B, Capacity 30

## Now Test It!

1. Go to **Students → Add New Student**
2. Select **Class:** JSS1
3. Watch the **Arm** dropdown enable
4. See only JSS1 arms: A, B, C
5. Select an arm
6. Complete the form and create the student

## Visual Flow

```
Classes Page
    ↓
Click "+ Add Arm" on JSS1 card
    ↓
Modal pops up
    ↓
Fill: Name = "A", Capacity = 40
    ↓
Click "Add Arm"
    ↓
Success! JSS1-A created
    ↓
Repeat for B, C, etc.
```

## Quick Tips

✅ **DO:**
- Create classes first, then add arms
- Use simple names: A, B, C, D
- Set realistic capacity: 35-45 students
- Assign teachers to arms

❌ **DON'T:**
- Don't create arms before classes exist
- Don't skip the capacity field
- Don't use very long arm names

## Troubleshooting

**Problem:** I don't see the "+ Add Arm" button

**Solution:** Refresh the page. The button should appear on each class card below the student count.

---

**Problem:** Modal doesn't open when I click

**Solution:** Clear your browser cache (Ctrl+Shift+R) and try again.

---

**Problem:** Can't find teachers in the dropdown

**Solution:** First add teachers in the Teachers section, then come back to add arms.

## That's It!

You now know how to:
1. ✅ Create Classes (JSS1, JSS2, etc.)
2. ✅ Add Arms to each class (A, B, C)
3. ✅ Assign teachers to arms
4. ✅ Enroll students into specific arms

Your class structure is ready! 🎓
