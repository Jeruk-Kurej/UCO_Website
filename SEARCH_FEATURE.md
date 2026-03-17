# Search Feature Documentation

## Overview
Added search functionality to three main list pages:
1. **User List** - Search students/alumni by name, email, username, or NIS
2. **Business List** - Search businesses by name, description, owner, or category
3. **Business Category List** - Search categories by name or description

## Implementation Details

### 1. User List Search
**File**: `app/Http/Controllers/UserController.php`

**Search Fields**:
- Name (nama mahasiswa/alumni)
- Email
- Username
- NIS (from extended_data JSON field)

**Query Example**:
```php
$query->where(function($q) use ($search) {
    $q->where('name', 'LIKE', "%{$search}%")
      ->orWhere('email', 'LIKE', "%{$search}%")
      ->orWhere('username', 'LIKE', "%{$search}%")
      ->orWhere('extended_data->nis', 'LIKE', "%{$search}%");
});
```

**Features**:
- Maintains pagination (20 items per page)
- Preserves search parameter in pagination links
- Clear button to reset search
- Search input with icon
- Statistics remain accurate during search

### 2. Business List Search
**File**: `app/Http/Controllers/BusinessController.php`

**Search Fields**:
- Business name (nama bisnis)
- Business description
- Owner name (through relationship)
- Business type/category (through relationship)

**Query Example**:
```php
$query->where(function($q) use ($search) {
    $q->where('name', 'LIKE', "%{$search}%")
      ->orWhere('description', 'LIKE', "%{$search}%")
      ->orWhereHas('user', function($userQuery) use ($search) {
          $userQuery->where('name', 'LIKE', "%{$search}%");
      })
      ->orWhereHas('businessType', function($typeQuery) use ($search) {
          $typeQuery->where('name', 'LIKE', "%{$search}%");
      });
});
```

**Features**:
- Works with both "Browse All" and "My Businesses" tabs
- Maintains pagination (15 items per page)
- Preserves both 'search' and 'my' query parameters
- Search across relationships (User, BusinessType)
- Clear button respects current tab context

### 3. Business Type/Category List Search
**File**: `app/Http/Controllers/BusinessTypeController.php`

**Search Fields**:
- Category name
- Category description

**Query Example**:
```php
$query->where(function($q) use ($search) {
    $q->where('name', 'LIKE', "%{$search}%")
      ->orWhere('description', 'LIKE', "%{$search}%");
});
```

**Features**:
- Public access (no authentication required)
- Maintains pagination (15 items per page)
- Preserves search parameter in pagination links
- Clear button to reset search

## User Interface

### Search Bar Components
All three pages have consistent search UI:

1. **Search Input**:
   - Icon (magnifying glass) on the left
   - Placeholder text specific to each page
   - Pre-filled with current search value
   - Full-width responsive design

2. **Search Button**:
   - Dark gray background (#111827)
   - White text
   - Hover effect (lighter gray)
   - "Search" label

3. **Clear Button** (conditional):
   - Only shown when search is active
   - Light gray background (#F3F4F6)
   - "Clear" label
   - Redirects to base URL (resets search)

### Search Bar Placement

**User List**: 
- Below page header
- Above statistics cards
- Contained in white card with border

**Business List**:
- Below tab navigation
- Above business cards grid
- Gray background (#F9FAFB) section
- Hidden input maintains 'my' parameter for tab context

**Business Type List**:
- Below page header
- Above table
- Contained in white card with border

## Technical Notes

### Pagination Preservation
```php
// Append search parameter to pagination links
->paginate(20)->appends(['search' => $search])
```

### Query Parameter Handling
```php
// Get search from request
$search = $request->get('search');

// Apply only if search exists
if ($search) {
    // ... query logic
}
```

### Case-Insensitive Search
- Uses MySQL's default case-insensitive LIKE operator
- Works with Indonesian characters and special characters
- Partial matching (substring search)

### Performance Considerations
- LIKE queries with leading wildcard can be slow on large datasets
- Consider adding indexes for frequently searched columns:
  - `users`: name, email, username
  - `businesses`: name
  - `business_types`: name

## Usage Examples

### User Search
```
Search: "budi"
Results: Users with name/email/username/NIS containing "budi"
```

### Business Search
```
Search: "cafe"
Results: 
- Businesses with name containing "cafe"
- Businesses with description containing "cafe"
- Businesses owned by someone named "cafe"
- Businesses in category containing "cafe"
```

### Category Search
```
Search: "food"
Results: Categories with name/description containing "food"
```

## Future Enhancements

### Suggested Improvements:
1. **Advanced Filters**: Add dropdown filters for role, business type, etc.
2. **Search Suggestions**: Auto-complete based on existing data
3. **Full-Text Search**: Implement MySQL FULLTEXT or Elasticsearch for better performance
4. **Search History**: Save recent searches per user
5. **Export Search Results**: Export filtered data to Excel
6. **Fuzzy Search**: Handle typos and spelling mistakes
7. **Date Range Filter**: Filter by creation date
8. **Status Filter**: Filter by active/inactive status

### Performance Optimization:
1. Add database indexes:
```sql
ALTER TABLE users ADD INDEX idx_name (name);
ALTER TABLE users ADD INDEX idx_email (email);
ALTER TABLE businesses ADD INDEX idx_name (name);
ALTER TABLE business_types ADD INDEX idx_name (name);
```

2. Consider full-text index for description fields:
```sql
ALTER TABLE businesses ADD FULLTEXT idx_description (description);
```

## Testing Checklist

- [x] User search by name works
- [x] User search by email works
- [x] User search by username works
- [x] User search by NIS works
- [x] Business search by name works
- [x] Business search by description works
- [x] Business search by owner name works
- [x] Business search by category works
- [x] Business search works in "My Businesses" tab
- [x] Category search by name works
- [x] Category search by description works
- [x] Pagination preserved with search
- [x] Clear button resets search
- [x] Empty search shows all results
- [x] No errors in controllers

## Files Modified

1. **Controllers**:
   - `app/Http/Controllers/UserController.php` - Added search parameter and query filtering
   - `app/Http/Controllers/BusinessController.php` - Added search with relationship queries
   - `app/Http/Controllers/BusinessTypeController.php` - Added basic search

2. **Views**:
   - `resources/views/users/index.blade.php` - Added search bar
   - `resources/views/businesses/index.blade.php` - Added search bar with tab context
   - `resources/views/business-types/index.blade.php` - Added search bar

## Commit Message Suggestion
```
feat: Add search functionality to User, Business, and Category lists

- User list: Search by name, email, username, NIS
- Business list: Search by name, description, owner, category
- Category list: Search by name, description
- Maintain pagination with search parameters
- Add clear button to reset search
- Preserve tab context in business search
```
